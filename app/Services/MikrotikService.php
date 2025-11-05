<?php

namespace App\Services;

use App\Models\MikrotikConnection;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Exception;

class MikrotikService
{
    protected $client;

    public function __construct(MikrotikConnection $conn)
    {
        // Pengecekan koneksi dan konfigurasi
        $config = (new Config())
            ->set('host', $conn->host)
            ->set('user', $conn->username)
            ->set('pass', $conn->password)
            ->set('port', (int)$conn->port)
            ->set('ssl', (bool)$conn->use_ssl);

        try {
            $this->client = new Client($config);
        } catch (\RouterOS\Exceptions\ClientException $e) {
             // Lempar exception yang lebih informatif jika koneksi gagal
             throw new Exception("Gagal terhubung ke Mikrotik {$conn->name}: " . $e->getMessage());
        }
    }

    /**
     * Helper function to execute command and check for Mikrotik !trap errors.
     * @throws Exception
     */
    public function comm(string $path, array $params = [])
    {
        try {
            $query = new Query($path, $params);
            $response = $this->client->query($query)->read();

            // === PERBAIKAN KRITIS: CEK SEMUA ITEM RESPONSE UNTUK MENDETEKSI !trap ===
            // Cek jika !trap ada di root response
            if (isset($response['!trap'])) {
                 $errorMessage = $response['!trap']['message'] ?? 'Unknown Mikrotik Error (Root Trap).';
                 throw new Exception("Perintah Mikrotik Ditolak: " . $errorMessage);
            }
            
            // Cek jika !trap ada di salah satu item response (misal: index 0)
            foreach ($response as $res) {
                if (is_array($res) && isset($res['!trap'])) {
                    $errorMessage = $res['!trap']['message'] ?? 'Unknown Mikrotik Error (Item Trap).';
                    throw new Exception("Perintah Mikrotik Ditolak: " . $errorMessage);
                }
            }
            
            // Jika tidak ada trap, perintah dianggap berhasil.
            return $response;

        } catch (\RouterOS\Exceptions\ClientException $e) {
            // Tangkap exception level library (misal: timeout, koneksi putus)
            throw new Exception('Gagal menjalankan command: ' . $e->getMessage());
        } catch (Exception $e) {
            // Tangkap exception umum lainnya, termasuk yang dilempar dari MikrotikService sendiri
            throw $e;
        }
    }

    public function getSecrets()
    {
        return $this->comm('/ppp/secret/print');
    }

    public function getActive()
    {
        return $this->comm('/ppp/active/print');
    }

    public function getProfiles()
    {
        return $this->comm('/ppp/profile/print');
    }
}
