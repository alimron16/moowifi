<?php

namespace App\Services\WhatsApp\Drivers;

use App\Services\WhatsApp\WhatsAppServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteDriver implements WhatsAppServiceInterface
{
    public function __construct(protected ?string $token = null)
    {
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function sendMessage(string $recipient, string $message, array $options = []): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'error' => 'API Token Fonnte belum dikonfigurasi.',
            ];
        }

        // Standardize phone number format for Indonesia (08... -> 628...)
        $phone = preg_replace('/[^0-9]/', '', $recipient);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->timeout(10)->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                return [
                    'success' => true,
                    'message_id' => $result['id'][0] ?? null,
                    'data' => $result,
                ];
            }

            return [
                'success' => false,
                'error' => $result['reason'] ?? 'Gagal mengirim pesan via Fonnte.',
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte send exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
