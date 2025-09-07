<?php

namespace App\Services\Brokers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Str;

class MetaApiService
{
    private Client $http;
    private string $token;
    private int    $pollSeconds;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => config('brokers.metaapi.base_url'),
            'timeout'  => 20,
        ]);
        $this->token = (string) config('brokers.metaapi.token');
        $this->pollSeconds = (int) config('brokers.metaapi.poll_seconds', 12);
    }

    /**
     * Try to create & read an MT account to validate credentials.
     * Returns array: ['ok'=>bool, 'message'=>string]
     */
    public function verify(string $platform, string $server, string $login, string $password): array
    {
        if (!$this->token) {
            return ['ok' => false, 'message' => 'MetaApi token is missing on the server.'];
        }

        // MetaApi requires a 32-char transaction-id; reuse on 202 polling
        $tx = Str::upper(Str::random(32));

        // 1) Create account
        try {
            $resp = $this->http->post('/users/current/accounts', [
                'headers' => [
                    'auth-token'      => $this->token,
                    'transaction-id'  => $tx,
                    'Accept'          => 'application/json',
                ],
                'json' => [
                    'name'     => 'verify-'.$platform.'-'.substr($tx, 0, 6),
                    'login'    => (string) $login,
                    'password' => (string) $password,
                    'server'   => (string) $server,
                    'platform' => $platform,     // 'mt4' or 'mt5'
                    'type'     => 'cloud-g2',    // recommended tier
                    'magic'    => 0,
                ],
            ]);
        } catch (ClientException $e) {
            $res  = $e->getResponse();
            $body = $res ? json_decode((string) $res->getBody(), true) : null;
            $detail = $body['details'] ?? $body['message'] ?? '';
            // Common validation errors from MetaApi:
            if (($res && $res->getStatusCode() === 400) && $detail === 'E_AUTH') {
                return ['ok'=>false, 'message'=>'Login or password is incorrect (E_AUTH).'];
            }
            if (($res && $res->getStatusCode() === 400) && $detail === 'E_SRV_NOT_FOUND') {
                return ['ok'=>false, 'message'=>'Server not found (E_SRV_NOT_FOUND). Please use the exact server name.'];
            }
            return ['ok'=>false, 'message'=>'MetaApi error: '.($detail ?: 'unexpected client error')];
        }

        $status = $resp->getStatusCode();
        $data   = json_decode((string) $resp->getBody(), true) ?? [];
        $accountId = $data['_id'] ?? null;

        // If creation is immediately deployed (201), we can read its connectionStatus
        // If 202 Accepted, poll the same tx for a short period.
        if ($status === 202) {
            // small sync poll so the user gets instant feedback
            sleep(min($this->pollSeconds, 12));
            $resp2 = $this->http->post('/users/current/accounts', [
                'headers' => [
                    'auth-token'      => $this->token,
                    'transaction-id'  => $tx, // reuse to fetch the same result
                    'Accept'          => 'application/json',
                ],
                'json' => [
                    'name'     => 'verify-'.$platform.'-'.substr($tx, 0, 6),
                    'login'    => (string) $login,
                    'password' => (string) $password,
                    'server'   => (string) $server,
                    'platform' => $platform,
                    'type'     => 'cloud-g2',
                    'magic'    => 0,
                ],
            ]);
            $data     = json_decode((string) $resp2->getBody(), true) ?? [];
            $accountId = $data['_id'] ?? $accountId;
        }

        if (!$accountId) {
            return ['ok'=>false, 'message'=>'Could not create account on MetaApi.'];
        }

        // 2) Read account to see connectionStatus
        // GET /users/current/accounts/:accountId
        $read = $this->http->get("/users/current/accounts/{$accountId}", [
            'headers' => ['auth-token' => $this->token, 'Accept' => 'application/json'],
        ]);
        $acc = json_decode((string) $read->getBody(), true) ?? [];

        // Clean up the temporary account (we only needed to validate creds)
        try {
            $this->http->delete("/users/current/accounts/{$accountId}", [
                'headers' => ['auth-token' => $this->token, 'Accept' => 'application/json'],
            ]);
        } catch (\Throwable $e) { /* ignore */ }

        $conn = $acc['connectionStatus'] ?? 'UNKNOWN';
        // Typical values: CONNECTED, DISCONNECTED, AUTHORIZATION_FAILED
        if ($conn === 'CONNECTED') {
            return ['ok'=>true, 'message'=>"✅ Verified. Connected to {$server}."];
        }
        if ($conn === 'AUTHORIZATION_FAILED') {
            return ['ok'=>false, 'message'=>'Login or password is incorrect (AUTHORIZATION_FAILED).'];
        }

        return ['ok'=>false, 'message'=>"Account created but status is {$conn}. Try again with the exact server name or investor password."];
    }

    /**
     * Lightweight fuzzy search for server names (MetaApi endpoint)
     */
    public function searchServers(string $version, string $query): array
    {
        $resp = $this->http->get("/known-mt-servers/{$version}/search", [
            'headers' => ['auth-token' => $this->token, 'Accept' => 'application/json'],
            'query'   => ['query' => $query],
        ]);
        return json_decode((string) $resp->getBody(), true) ?? [];
    }
}
