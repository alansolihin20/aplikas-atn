<?php

namespace App\Http\Controllers\mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PPPoeUser;
use App\Models\MikrotikConnection;
use App\Services\MikrotikService;
use Exception;

class PppoeController extends Controller
{
    /**
     * Tampilkan daftar PPPoE dan otomatis sync dari Mikrotik
     */
    public function index(Request $request)
    {
        $connections = MikrotikConnection::all();
        $connectionId = $request->input('connection_id', $connections->first()?->id);
        $connection = $connectionId ? MikrotikConnection::find($connectionId) : null;

        $profiles = [];
        $mikrotikError = null;

        if ($connection) {
            try {
                $svc = new MikrotikService($connection);

                // Ambil profile
                $profiles = $svc->comm('/ppp/profile/print');

                // === AUTO SYNC PPP SECRET ===
                $secrets = $svc->comm('/ppp/secret/print');
                foreach ($secrets as $s) {
                    PPPoeUser::updateOrCreate(
                        ['name' => $s['name'] ?? ''],
                        [
                            'password'       => $s['password'] ?? '',
                            'profile'        => $s['profile'] ?? '',
                            'service'        => $s['service'] ?? 'pppoe',
                            'remote_address' => $s['remote-address'] ?? '',
                            'local_address'  => $s['local-address'] ?? '',
                            'disabled'       => ($s['disabled'] ?? 'false') === 'true',
                            'comment'        => $s['comment'] ?? '',
                        ]
                    );
                }

            } catch (Exception $e) {
                $mikrotikError = "Gagal konek ke Mikrotik: " . $e->getMessage();
            }
        }

        // Ambil data dari DB setelah sync
        $items = PPPoeUser::latest()->paginate(20);

        return view('mikrotik.pppoe', compact(
            'items', 'connections', 'profiles', 'connectionId', 'mikrotikError'
        ));
    }

    /**
     * Tambah user PPPoE ke database dan Mikrotik
     */
    public function store(Request $request)
    {
        $request->validate([
            'connection_id'   => 'required|exists:mikrotik_connections,id',
            'name'            => 'required|string|max:100',
            'password'        => 'required|string|max:100',
            'service'         => 'nullable|string|max:50',
            'profile'         => 'nullable|string|max:100',
            'local_address'   => 'nullable|string|max:20',
            'remote_address'  => 'nullable|string|max:20',
            'comment'         => 'nullable|string|max:255',
        ]);

        

        try {
            // Ambil koneksi Mikrotik
            $connection = MikrotikConnection::find($request->connection_id);
            if (!$connection) throw new Exception("Koneksi Mikrotik tidak ditemukan.");

            $svc = new MikrotikService($connection);

            // Kirim perintah ke Mikrotik
            $response = $svc->comm('/ppp/secret/add', [
                'name'           => $request->name,
                'password'       => $request->password,
                'service'        => $request->service ?? 'pppoe',
                'profile'        => $request->profile ?? '',
                'local-address'  => $request->local_address ?? '',
                'remote-address' => $request->remote_address ?? '',
                'comment'        => $request->comment ?? '',
            ]);

            // Pastikan respons Mikrotik tidak kosong
            if (isset($response['!trap'])) {
                throw new Exception('Mikrotik error: ' . ($response['!trap'][0]['message'] ?? 'Unknown'));
            }

            // Simpan juga ke DB
            PPPoeUser::create($request->except('connection_id'));

            return redirect()->back()->with('success', 'PPPoE berhasil ditambahkan ke Mikrotik dan database!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan PPPoE: ' . $e->getMessage());
        }
    }

    /**
     * Update PPPoE user di Mikrotik dan DB
     */
    public function update(Request $request, PPPoeUser $pppoe)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'password'       => 'nullable|string|max:100',
            'profile'        => 'nullable|string|max:100',
            'service'        => 'nullable|string|max:50',
            'remote_address' => 'nullable|string|max:20',
            'local_address'  => 'nullable|string|max:20',
            'comment'        => 'nullable|string|max:255',
        ]);

        $pppoe->update($data);

        try {
            $connection = MikrotikConnection::find($pppoe->mikrotik_connection_id ?? MikrotikConnection::first()->id);
            if (!$connection) throw new Exception("Koneksi Mikrotik tidak ditemukan.");

            $svc = new MikrotikService($connection);

            // Pastikan ada id Mikrotik
            if (empty($pppoe->mikrotik_id)) {
                throw new Exception("User belum disinkronisasi dari Mikrotik.");
            }

            $svc->comm('/ppp/secret/set', [
                '.id'            => '*' . $pppoe->mikrotik_id,
                'name'           => $pppoe->name,
                'password'       => $pppoe->password ?? '',
                'service'        => $pppoe->service ?? 'pppoe',
                'profile'        => $pppoe->profile ?? '',
                'local-address'  => $pppoe->local_address ?? '',
                'remote-address' => $pppoe->remote_address ?? '',
                'comment'        => $pppoe->comment ?? '',
            ]);

            return redirect()->route('pppoe.index')->with('success', 'PPPoE berhasil diperbarui!');
        } catch (Exception $e) {
            return redirect()->route('pppoe.index')->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Hapus user PPPoE dari Mikrotik dan DB
     */
    public function destroy(PPPoeUser $pppoe)
    {
        try {
            $connection = MikrotikConnection::first();
            if (!$connection) throw new Exception("Koneksi Mikrotik tidak ditemukan.");

            $svc = new MikrotikService($connection);

            if (!empty($pppoe->mikrotik_id)) {
                $svc->comm('/ppp/secret/remove', [
                    '.id' => '*' . $pppoe->mikrotik_id,
                ]);
            }

            $pppoe->delete();
            return redirect()->route('pppoe.index')->with('success', 'User PPPoE dihapus.');
        } catch (Exception $e) {
            return redirect()->route('pppoe.index')->with('error', 'Gagal menghapus PPPoE: ' . $e->getMessage());
        }
    }
}
