<?php

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Router;
use App\Services\BillingService;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// 1. Check Due Status: Mark UNPAID invoices as OVERDUE if due_date is passed
Artisan::command('billing:check-due', function () {
    $count = Invoice::withoutGlobalScopes()
        ->where('status', 'UNPAID')
        ->whereDate('due_date', '<', now()->toDateString())
        ->update(['status' => 'OVERDUE']);

    $this->info("Updated {$count} invoices to OVERDUE.");
})->purpose('Memperbarui status tagihan yang melewati tanggal jatuh tempo menjadi OVERDUE');

// 2. Auto Isolation: Cut internet for overdue customers exceeding grace period
Artisan::command('billing:auto-cut', function (BillingService $billingService) {
    $overdueInvoices = Invoice::withoutGlobalScopes()
        ->with('customer')
        ->whereIn('status', ['UNPAID', 'OVERDUE'])
        ->whereDate('due_date', '<', now()->toDateString())
        ->get();

    $isolatedCount = 0;
    foreach ($overdueInvoices as $invoice) {
        $customer = $invoice->customer;
        if ($customer && $customer->auto_cut_enabled && $customer->status !== 'ISOLATED') {
            $daysPastDue = now()->diffInDays($invoice->due_date);
            if ($daysPastDue >= $customer->grace_period_days) {
                $billingService->isolateCustomer($customer);
                $isolatedCount++;
            }
        }
    }

    $this->info("Executed auto-isolation on {$isolatedCount} customer(s).");
})->purpose('Mengeksekusi isolir otomatis bagi pelanggan yang melewati masa toleransi');

// 3. Ping Routers: Heartbeat check for all routers
Artisan::command('mikrotik:ping', function (MikrotikService $mikrotikService) {
    $routers = Router::withoutGlobalScopes()->get();
    foreach ($routers as $router) {
        $mikrotikService->testConnection($router);
    }
    $this->info("Pinged {$routers->count()} router(s).");
})->purpose('Pemeriksaan detak jantung konektivitas router MikroTik');

// 4. Monthly Invoice Generation: Auto generate invoices for customers on billing_day
Artisan::command('billing:generate-monthly', function (BillingService $billingService) {
    $today = now();
    $currentDay = (int) $today->format('j');

    $customers = Customer::withoutGlobalScopes()
        ->whereIn('status', ['ACTIVE', 'UNPAID'])
        ->where('billing_day', $currentDay)
        ->get();

    $generated = 0;
    $periodStart = $today->copy()->startOfMonth();
    $periodEnd = $today->copy()->endOfMonth();

    foreach ($customers as $customer) {
        $exists = Invoice::withoutGlobalScopes()
            ->where('customer_id', $customer->id)
            ->whereYear('period_start', $periodStart->year)
            ->whereMonth('period_start', $periodStart->month)
            ->exists();

        if (!$exists) {
            $dueDate = $today->copy()->day((int)$customer->due_day);
            if ($dueDate->lessThan($today)) {
                $dueDate = $dueDate->addMonth();
            }
            $billingService->generateInvoice($customer, $periodStart, $periodEnd, $dueDate);
            $generated++;
        }
    }

    $this->info("Generated {$generated} monthly invoice(s).");
})->purpose('Menerbitkan tagihan bulanan otomatis bagi pelanggan yang siklusnya jatuh hari ini');

// 5. Send Payment Reminders (H-3, H-1, Due Date)
Artisan::command('billing:send-reminders', function (\App\Services\WhatsApp\WhatsAppService $waService) {
    $today = now()->startOfDay();
    $h3 = $today->copy()->addDays(3)->toDateString();
    $h1 = $today->copy()->addDays(1)->toDateString();
    $dueToday = $today->toDateString();

    $targetInvoices = Invoice::withoutGlobalScopes()
        ->with(['customer', 'tenant'])
        ->whereIn('status', ['UNPAID', 'OVERDUE'])
        ->whereIn('due_date', [$h3, $h1, $dueToday])
        ->get();

    $sent = 0;
    foreach ($targetInvoices as $invoice) {
        $customer = $invoice->customer;
        $tenant = $invoice->tenant;
        if ($customer && !empty($customer->phone) && $tenant) {
            $diff = now()->diffInDays($invoice->due_date, false);
            $prefix = $diff === 0 ? 'Hari ini adalah tanggal jatuh tempo' : "Masa jatuh tempo tersisa {$diff} hari lagi";

            $message = "Pengingat Tagihan: Halo {$customer->name}, {$prefix} untuk tagihan {$invoice->invoice_number} sebesar Rp " . number_format($invoice->total_amount, 0, ',', '.') . ". Pembayaran: {$invoice->payment_url}";
            $waService->sendMessage($tenant, $customer->phone, $message);
            $sent++;
        }
    }

    $this->info("Sent {$sent} payment reminder(s).");
})->purpose('Mengirim pengingat tagihan H-3, H-1, dan hari jatuh tempo via WhatsApp');

// Register schedules
Schedule::command('billing:generate-monthly')->dailyAt('00:01');
Schedule::command('billing:check-due')->dailyAt('00:05');
Schedule::command('billing:auto-cut')->dailyAt('00:15');
Schedule::command('billing:send-reminders')->dailyAt('09:00');
Schedule::command('mikrotik:ping')->everyFiveMinutes();
