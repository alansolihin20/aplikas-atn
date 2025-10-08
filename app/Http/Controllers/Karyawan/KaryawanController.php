<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Assuming you are using the User model for karyawan data
use App\Models\KaryawanModel; // If you have a specific Karyawan model, use it instead
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
        public function index()
        {
            // Mengambil semua pengguna dengan peran 'teknisi'
            $users = User::where('role', 'teknisi')->get();

            // Mengambil semua data dari KaryawanModel
            $karyawans = KaryawanModel::with('user')->get();

            // Mengirimkan kedua data ke view
            return view('karyawan.karyawandata', compact('users', 'karyawans'));
        }

       public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'nik' => 'required|string|unique:karyawan,nik',
                'jabatan' => 'required|string',
                'tanggal_masuk' => 'required|date',
                'gaji_pokok' => 'required|numeric',
                'tunjangan' => 'nullable|numeric',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ], [
                'email.unique' => 'Email ini sudah terdaftar, silakan pakai yang lain.',
                'nik.unique'   => 'NIK ini sudah terdaftar, cek lagi datanya.',
                'foto.image'   => 'File harus berupa gambar.',
                'foto.mimes'   => 'Format foto harus jpg, jpeg, atau png.',
            ]);

            $fileName = null;
            if ($request->hasFile('foto')) {
                $fileName = time() . '.' . $request->foto->extension();
                $request->foto->move(public_path('karyawan'), $fileName);
            }
            try {
                // Simpan ke tabel users
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'teknisi',
                ]);

                // Simpan ke tabel karyawan
                KaryawanModel::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'nik' => $request->nik,
                    'jabatan' => $request->jabatan,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'gaji_pokok' => $request->gaji_pokok,
                    'tunjangan' => $request->tunjangan ?? 0,
                    'foto' => $fileName,
                    'status' => 'Aktif',
                ]);

            } catch (\Exception $e) {
                return redirect()
                    ->route('karyawan.index')
                    ->with('error', 'Gagal menambahkan karyawan: ' . $e->getMessage());
            }

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
        }




        public function update(Request $request, $id)
        {
            $karyawan = KaryawanModel::findOrFail($id);

             $request->validate([
                'jabatan' => 'required|string',
                'gaji_pokok' => 'required|numeric',
                'tunjangan' => 'nullable|numeric',
                'status' => 'required|string|in:aktif,nonaktif',
            ]);

            try {
                // Update tabel karyawan
               $karyawan->update([
                'jabatan' => $request->jabatan,
                'gaji_pokok' => $request->gaji_pokok,
                'tunjangan' => $request->tunjangan ?? 0,
                'status' => $request->status,
                    ]);
            } catch (\Exception $e) {
                return redirect()
                ->route('karyawan.index')
                ->with('error', 'Gagal memperbarui karyawan: ' . $e->getMessage());
            }

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
        }

        public function destroy($id)
        {
            $karyawan = KaryawanModel::findOrFail($id);
            $user = User::findOrFail($karyawan->user_id);

            try {
                // Hapus data karyawan
                $karyawan->delete();

                // Hapus data user terkait
                $user->delete();
            } catch (\Exception $e) {
                return redirect()
                ->route('karyawan.index')
                ->with('error', 'Gagal menghapus karyawan: ' . $e->getMessage());
            }

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
        }

}
