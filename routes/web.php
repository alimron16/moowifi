<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Public\PaymentPageController;
use App\Http\Controllers\SuperAdmin\SaasPlanController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SystemMonitoringController;
use App\Http\Controllers\SuperAdmin\TenantManagementController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\ManualPaymentController;
use App\Http\Controllers\Tenant\NotificationController;
use App\Http\Controllers\Tenant\PackageController;
use App\Http\Controllers\Tenant\PaymentMethodController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\RouterController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Webhook\PaymentWebhookController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Public Payment Link Routes (No login required)
Route::get('/pay/{token}', [PaymentPageController::class, 'show'])->name('payment.show');
Route::post('/pay/{token}/gateway', [PaymentPageController::class, 'payGateway'])->name('payment.pay-gateway');
Route::post('/pay/{token}/manual', [PaymentPageController::class, 'submitManualTransfer'])->name('payment.submit-manual');

// Webhook Endpoints (Multi-Payment Gateway)
Route::post('/api/webhooks/duitku', [PaymentWebhookController::class, 'handleDuitku'])->name('webhook.duitku');
Route::post('/api/webhooks/midtrans', [PaymentWebhookController::class, 'handleMidtrans'])->name('webhook.midtrans');
Route::post('/api/webhooks/xendit', [PaymentWebhookController::class, 'handleXendit'])->name('webhook.xendit');
Route::post('/api/webhooks/tripay', [PaymentWebhookController::class, 'handleTripay'])->name('webhook.tripay');

// -------------------------------------------------------------
// SUPER ADMIN ROUTES
// -------------------------------------------------------------
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Tenant Management (Section 7: All Tenants, Active, Trial, Suspended, Detail Tenant)
    Route::get('/tenants', [TenantManagementController::class, 'index'])->name('tenants.index');
    Route::post('/tenants', [TenantManagementController::class, 'store'])->name('tenants.store');
    Route::get('/tenants/{tenant}', [TenantManagementController::class, 'show'])->name('tenants.show');
    Route::post('/tenants/{tenant}/toggle-status', [TenantManagementController::class, 'toggleStatus'])->name('tenants.toggle-status');

    // SaaS Plans
    Route::get('/plans', [SaasPlanController::class, 'index'])->name('plans.index');
    Route::post('/plans', [SaasPlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{plan}', [SaasPlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{plan}', [SaasPlanController::class, 'destroy'])->name('plans.destroy');

    // Subscriptions & SaaS Payments
    Route::get('/subscriptions', [\App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions', [\App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::put('/subscriptions/{subscription}', [\App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::delete('/subscriptions/{subscription}', [\App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
    Route::get('/payments', [\App\Http\Controllers\SuperAdmin\SubscriptionController::class, 'payments'])->name('payments.index');

    // System Monitoring (Section 7: Routers, Queue, Scheduler, Webhook, System Health)
    Route::get('/monitoring', [SystemMonitoringController::class, 'index'])->name('monitoring.index');

    // Platform Level Settings & Services
    Route::get('/payment-providers', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'paymentProviders'])->name('payment-providers.index');
    Route::post('/payment-providers', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'updatePaymentProviders'])->name('payment-providers.update');
    Route::get('/whatsapp', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'whatsapp'])->name('whatsapp.index');
    Route::post('/whatsapp', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'updateWhatsapp'])->name('whatsapp.update');
    Route::get('/email', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'email'])->name('email.index');
    Route::post('/email', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'updateEmail'])->name('email.update');
    Route::get('/logs', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'logs'])->name('logs.index');
    Route::get('/announcements', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'announcements'])->name('announcements.index');
    Route::post('/announcements', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::get('/support', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'support'])->name('support.index');
    Route::get('/settings', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SuperAdmin\PlatformSettingsController::class, 'updateSettings'])->name('settings.update');
});

// -------------------------------------------------------------
// TENANT ADMIN ROUTES
// -------------------------------------------------------------
Route::middleware(['auth', 'tenant_user'])->name('tenant.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/force-isolate', [CustomerController::class, 'forceIsolate'])->name('customers.force-isolate');
    Route::post('customers/{customer}/force-restore', [CustomerController::class, 'forceRestore'])->name('customers.force-restore');

    // Packages
    Route::resource('packages', PackageController::class)->except(['create', 'show', 'edit']);

    // Invoices & Billing
    Route::get('billing/payments', [InvoiceController::class, 'payments'])->name('invoices.payments');
    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update', 'destroy']);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');
    Route::post('invoices/{invoice}/send-whatsapp', [InvoiceController::class, 'sendWhatsApp'])->name('invoices.send-whatsapp');

    // Manual Payment Verification
    Route::get('billing/manual-payments', [ManualPaymentController::class, 'index'])->name('manual-payments.index');
    Route::post('billing/manual-payments/{confirmation}/approve', [ManualPaymentController::class, 'approve'])->name('manual-payments.approve');
    Route::post('billing/manual-payments/{confirmation}/reject', [ManualPaymentController::class, 'reject'])->name('manual-payments.reject');

    // Routers & MikroTik Subviews
    Route::get('routers/online-users', [RouterController::class, 'onlineUsers'])->name('routers.online-users');
    Route::get('routers/profiles', [RouterController::class, 'profiles'])->name('routers.profiles');
    Route::get('routers/logs', [RouterController::class, 'logs'])->name('routers.logs');
    Route::post('routers/{router}/kick-user', [RouterController::class, 'kickUser'])->name('routers.kick-user');
    Route::resource('routers', RouterController::class)->except(['show', 'edit', 'update']);
    Route::post('routers/{router}/test', [RouterController::class, 'testConnection'])->name('routers.test');

    // Payment Methods & Transaction Logs
    Route::get('payments/methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::post('payments/methods/manual', [PaymentMethodController::class, 'storeManual'])->name('payment-methods.store-manual');
    Route::get('payments/gateway', [PaymentMethodController::class, 'gateway'])->name('payment-methods.gateway');
    Route::post('payments/gateway', [PaymentMethodController::class, 'updateGateway'])->name('payment-methods.update-gateway');
    Route::get('payments/transactions', [PaymentMethodController::class, 'transactions'])->name('payment-methods.transactions');
    Route::delete('payments/methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

    // WhatsApp Notifications & Logs
    Route::get('notifications/logs', [NotificationController::class, 'logs'])->name('notifications.logs');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings');
    Route::post('notifications/test', [NotificationController::class, 'sendTestMessage'])->name('notifications.test');
    Route::post('notifications/qr/generate', [NotificationController::class, 'generateQr'])->name('notifications.qr.generate');
    Route::get('notifications/qr/status', [NotificationController::class, 'checkQrStatus'])->name('notifications.qr.status');
    Route::post('notifications/qr/disconnect', [NotificationController::class, 'disconnectQr'])->name('notifications.qr.disconnect');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/business', [SettingsController::class, 'updateBusiness'])->name('settings.business');
    Route::post('settings/billing-policy', [SettingsController::class, 'updateBillingPolicy'])->name('settings.billing-policy');
    Route::post('settings/email', [SettingsController::class, 'updateEmailSettings'])->name('settings.email');
    Route::post('settings/test-email', [SettingsController::class, 'testEmail'])->name('settings.test-email');
    Route::post('settings/templates', [SettingsController::class, 'updateTemplates'])->name('settings.templates');
    Route::post('settings/users', [SettingsController::class, 'storeUser'])->name('settings.store-user');
});
