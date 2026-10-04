<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('platform_settings')) {
                $email = \App\Models\PlatformSetting::get('platform_email');
                if (is_array($email) && !empty($email['mail_host']) && !empty($email['mail_username'])) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $email['mail_host'],
                        'mail.mailers.smtp.port' => (int)($email['mail_port'] ?? 587),
                        'mail.mailers.smtp.encryption' => $email['mail_encryption'] ?? 'tls',
                        'mail.mailers.smtp.username' => $email['mail_username'],
                        'mail.mailers.smtp.password' => $email['mail_password'] ?? '',
                        'mail.from.address' => $email['mail_from_address'] ?? 'noreply@moowifi.id',
                        'mail.from.name' => $email['mail_from_name'] ?? 'MooWiFi SaaS Platform',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore during migrations / console operations
        }
    }
}
