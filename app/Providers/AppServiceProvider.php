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
                    $port = (int)($email['mail_port'] ?? 587);
                    $encryption = strtolower($email['mail_encryption'] ?? 'tls');
                    $scheme = ($port === 465 || $encryption === 'ssl') ? 'smtps' : 'smtp';

                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.scheme' => $scheme,
                        'mail.mailers.smtp.host' => $email['mail_host'],
                        'mail.mailers.smtp.port' => $port,
                        'mail.mailers.smtp.encryption' => $encryption,
                        'mail.mailers.smtp.username' => $email['mail_username'],
                        'mail.mailers.smtp.password' => $email['mail_password'] ?? '',
                        'mail.mailers.smtp.timeout' => 15,
                        'mail.from.address' => $email['mail_from_address'] ?? $email['mail_username'],
                        'mail.from.name' => $email['mail_from_name'] ?? 'MooWiFi SaaS Platform',
                    ]);

                    if (app()->bound('mail.manager')) {
                        app('mail.manager')->forgetMailers();
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silently ignore during migrations / console operations
        }
    }
}
