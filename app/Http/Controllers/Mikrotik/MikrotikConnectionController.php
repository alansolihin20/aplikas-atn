<?php

namespace App\Http\Controllers\mikrotik;

use App\Http\Controllers\Controller;
use App\Models\MikrotikConnection;
use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Config as RouterConfig;
use RouterOS\Query;
use Exception;

class MikrotikConnectionController extends Controller
{
    public function index()
    {
        $items = MikrotikConnection::latest()->paginate(12);
        return view('mikrotik.index', compact('items'));
    }

    public function create()
    {
        return view('mikrotik.create');
    }

    


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'host' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:191',
            'password' => 'required|string',
            'use_ssl' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['use_ssl'] = (bool) ($data['use_ssl'] ?? false);

       MikrotikConnection::create([
            'name' => $data['name'],
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'password' => $data['password'], // akan terenkripsi oleh model
            'use_ssl' => $data['use_ssl'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('mikrotik.index')->with('success','Connection saved.');
    }

    public function show(MikrotikConnection $mikrotikConnection)
    {
        return view('mikrotik.index', ['conn' => $mikrotikConnection]);
    }

    public function edit(MikrotikConnection $mikrotikConnection)
    {
        return view('mikrotik.edit', ['item' => $mikrotikConnection]);
    }


     public function update(Request $request, MikrotikConnection $mikrotikConnection)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'host' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:191',
            'password' => 'nullable|string',
            'use_ssl' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!empty($data['password'])) {
            $mikrotikConnection->password = $data['password']; // set via mutator
        }
        $mikrotikConnection->update([
            'name' => $data['name'],
            'host' => $data['host'],
            'port' => $data['port'],
            'username' => $data['username'],
            'use_ssl' => (bool) ($data['use_ssl'] ?? false),
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()->route('mikrotik.index')->with('success','Connection updated.');
    }


     public function destroy(MikrotikConnection $mikrotikConnection)
    {
        $mikrotikConnection->delete();
        return redirect()->route('mikrotik.index')->with('success','Connection removed.');
    }
    


    public function testConnection(Request $request)
    {
        $payload = $request->validate([
            'connection_id' => 'nullable|exists:mikrotik_connections,id',
            'host' => 'nullable|ip',
            'port' => 'nullable|integer',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'use_ssl' => 'nullable|boolean',
        ]);

        if (!empty($payload['connection_id'])) {
            $conn = MikrotikConnection::findOrFail($payload['connection_id']);
            $host = $conn->host;
            $port = (int)$conn->port;
            $user = $conn->username;
            $pass = $conn->password; // decrypted by model accessor
            $use_ssl = (bool)$conn->use_ssl;
        } else {
            // ad-hoc test
            $host = $payload['host'] ?? null;
            $port = isset($payload['port']) ? (int)$payload['port'] : 8728;
            $user = $payload['username'] ?? null;
            $pass = $payload['password'] ?? null;
            $use_ssl = (bool) ($payload['use_ssl'] ?? false);

            if (!$host || !$user || !$pass) {
                return response()->json(['ok' => false, 'error' => 'host/username/password required'], 422);
            }
        }

        try {
            $cfg = new RouterConfig([
                'host' => $host,
                'user' => $user,
                'pass' => $pass,
                'port' => $port,
                'ssl'  => $use_ssl,
            ]);
            $client = new Client($cfg);

            // contoh: minta versi RouterOS
            $q = new Query('/system/resource/print');
            $resp = $client->query($q)->read();

            return response()->json(['ok' => true, 'resp' => $resp]);
        } catch (Exception $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }



}
