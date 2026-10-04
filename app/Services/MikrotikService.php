<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Router;
use Exception;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class MikrotikService
{
    /**
     * Build RouterOS client configuration
     */
    protected function getClient(Router $router): Client
    {
        // Use tunnel_ip if VPN_TUNNEL connection type, otherwise host
        $host = ($router->connection_type === 'VPN_TUNNEL' && $router->tunnel_ip)
            ? $router->tunnel_ip
            : $router->host;

        $config = (new Config())
            ->set('host', (string) $host)
            ->set('port', (int) ($router->port ?: 8728))
            ->set('user', (string) ($router->username ?? 'admin'))
            ->set('pass', (string) ($router->decrypted_password ?? ''))
            ->set('ssl', (bool) $router->use_ssl)
            ->set('timeout', 4)
            ->set('attempts', 2);

        return new Client($config);
    }

    /**
     * Test connection to router and update its status
     */
    public function testConnection(Router $router): array
    {
        try {
            $client = $this->getClient($router);
            $query = new Query('/system/resource/print');
            $response = $client->query($query)->read();

            $router->update([
                'status' => 'ONLINE',
                'last_seen_at' => now(),
                'last_error' => null,
            ]);

            return [
                'success' => true,
                'message' => 'Koneksi ke MikroTik berhasil.',
                'resource' => $response[0] ?? [],
            ];
        } catch (Exception $e) {
            $router->update([
                'status' => 'OFFLINE',
                'last_error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal terhubung ke MikroTik: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch active PPPoE connections
     */
    public function getActiveUsers(Router $router): array
    {
        try {
            $client = $this->getClient($router);
            $query = new Query('/ppp/active/print');
            return $client->query($query)->read();
        } catch (Exception) {
            return [];
        }
    }

    /**
     * Fetch PPP profiles from router
     */
    public function getPppProfiles(Router $router): array
    {
        try {
            $client = $this->getClient($router);
            $query = new Query('/ppp/profile/print');
            return $client->query($query)->read();
        } catch (Exception) {
            return [];
        }
    }

    /**
     * Create or update PPPoE Secret for customer
     */
    public function syncPppoeSecret(Router $router, Customer $customer, ?Package $package = null): bool
    {
        if (empty($customer->mikrotik_username)) {
            return false;
        }

        try {
            $client = $this->getClient($router);
            $username = $customer->mikrotik_username;
            $password = $customer->decrypted_mikrotik_password ?? '123456';
            $profile = ($package && $package->mikrotik_profile) ? $package->mikrotik_profile : 'default';

            // Find existing secret
            $query = (new Query('/ppp/secret/print'))
                ->where('name', $username);
            $existing = $client->query($query)->read();

            if (!empty($existing)) {
                $id = $existing[0]['.id'];
                $updateQuery = (new Query('/ppp/secret/set'))
                    ->equal('.id', $id)
                    ->equal('password', $password)
                    ->equal('profile', $profile)
                    ->equal('service', 'pppoe')
                    ->equal('comment', 'MWIFI: ' . $customer->customer_code . ' - ' . $customer->name);
                $client->query($updateQuery)->read();
            } else {
                $addQuery = (new Query('/ppp/secret/add'))
                    ->equal('name', $username)
                    ->equal('password', $password)
                    ->equal('profile', $profile)
                    ->equal('service', 'pppoe')
                    ->equal('comment', 'MWIFI: ' . $customer->customer_code . ' - ' . $customer->name);
                $client->query($addQuery)->read();
            }

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Ensure the isolation profile exists on the router, or create it automatically
     */
    public function ensureIsolationProfileExists(Router $router, string $isolationProfile = 'ISOLIR', ?Client $client = null): bool
    {
        try {
            $client = $client ?? $this->getClient($router);
            $query = (new Query('/ppp/profile/print'))->where('name', $isolationProfile);
            $existing = $client->query($query)->read();

            if (empty($existing)) {
                // Auto create the isolation profile with rate-limit
                $createQuery = (new Query('/ppp/profile/add'))
                    ->equal('name', $isolationProfile)
                    ->equal('rate-limit', '64k/64k')
                    ->equal('comment', 'Auto-created by MooWifi for customer isolation');
                $client->query($createQuery)->read();
            }

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Auto Cut: Switch PPPoE secret profile to ISOLIR and kick active session
     */
    public function isolateCustomer(Router $router, Customer $customer, string $isolationProfile = 'ISOLIR'): bool
    {
        if (empty($customer->mikrotik_username)) {
            return false;
        }

        try {
            $client = $this->getClient($router);
            $username = $customer->mikrotik_username;

            // Ensure isolation profile exists on router
            $this->ensureIsolationProfileExists($router, $isolationProfile, $client);

            // Find secret
            $query = (new Query('/ppp/secret/print'))->where('name', $username);
            $existing = $client->query($query)->read();

            if (!empty($existing)) {
                $id = $existing[0]['.id'];
                $updateQuery = (new Query('/ppp/secret/set'))
                    ->equal('.id', $id)
                    ->equal('profile', $isolationProfile);
                $client->query($updateQuery)->read();

                // Kick active session so customer immediately reconnects on isolation profile
                $this->disconnectActiveSession($router, $username, $client);
                return true;
            }

            return false;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Auto Restore: Restore PPPoE secret profile to original package profile and kick session
     */
    public function restoreCustomer(Router $router, Customer $customer, ?Package $package = null): bool
    {
        if (empty($customer->mikrotik_username)) {
            return false;
        }

        try {
            $client = $this->getClient($router);
            $username = $customer->mikrotik_username;
            $profile = ($package && $package->mikrotik_profile) ? $package->mikrotik_profile : 'default';

            $query = (new Query('/ppp/secret/print'))->where('name', $username);
            $existing = $client->query($query)->read();

            if (!empty($existing)) {
                $id = $existing[0]['.id'];
                $updateQuery = (new Query('/ppp/secret/set'))
                    ->equal('.id', $id)
                    ->equal('profile', $profile);
                $client->query($updateQuery)->read();

                // Kick active session so customer reconnects with normal bandwidth
                $this->disconnectActiveSession($router, $username, $client);
                return true;
            }

            return false;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Disconnect active PPPoE session
     */
    public function disconnectActiveSession(Router $router, string $username, ?Client $client = null): bool
    {
        try {
            $client = $client ?? $this->getClient($router);
            $query = (new Query('/ppp/active/print'))->where('name', $username);
            $activeList = $client->query($query)->read();

            foreach ($activeList as $session) {
                if (isset($session['.id'])) {
                    $removeQuery = (new Query('/ppp/active/remove'))->equal('.id', $session['.id']);
                    $client->query($removeQuery)->read();
                }
            }

            return true;
        } catch (Exception) {
            return false;
        }
    }
}
