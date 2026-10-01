<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Services\WhatsApp\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingService
{
    public function __construct(
        protected MikrotikService $mikrotikService,
        protected WhatsAppService $whatsAppService
    ) {}

    /**
     * Generate an invoice for customer
     */
    public function generateInvoice(Customer $customer, Carbon $periodStart, Carbon $periodEnd, Carbon $dueDate): ?Invoice
    {
        $tenant = $customer->tenant;
        $package = $customer->package;

        if (!$package) {
            return null;
        }

        // Generate formatted invoice number e.g. INV/202610/BDN/0001
        $count = Invoice::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereYear('created_at', $periodStart->year)
            ->whereMonth('created_at', $periodStart->month)
            ->count() + 1;

        $invoiceNumber = sprintf(
            'INV/%s%s/%s/%04d',
            $periodStart->format('Y'),
            $periodStart->format('m'),
            strtoupper($tenant->code),
            $count
        );

        return DB::transaction(function () use ($customer, $tenant, $package, $periodStart, $periodEnd, $dueDate, $invoiceNumber) {
            $invoice = Invoice::create([
                'tenant_id' => $tenant->id,
                'customer_id' => $customer->id,
                'invoice_number' => $invoiceNumber,
                'payment_token' => (string) Str::uuid(),
                'issue_date' => now()->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'subtotal' => $package->price,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'admin_fee' => 0,
                'unique_code' => rand(100, 999), // 3-digit code for manual verification
                'total_amount' => $package->price,
                'paid_amount' => 0,
                'status' => 'UNPAID',
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => 'Paket Internet ' . $package->name . ' (' . $package->download_speed . ')',
                'quantity' => 1,
                'unit_price' => $package->price,
                'total_price' => $package->price,
            ]);

            AuditLog::create([
                'tenant_id' => $tenant->id,
                'auditable_type' => Invoice::class,
                'auditable_id' => $invoice->id,
                'event' => 'INVOICE_GENERATED',
                'description' => "Tagihan {$invoiceNumber} berhasil dibuat untuk pelanggan {$customer->name}.",
                'new_values' => $invoice->toArray(),
            ]);

            return $invoice;
        });
    }

    /**
     * Process successful payment: Update invoice, customer active, auto-restore router, send WhatsApp receipt
     */
    public function markInvoicePaid(Invoice $invoice, Payment $payment): void
    {
        DB::transaction(function () use ($invoice, $payment) {
            $invoice->update([
                'status' => 'PAID',
                'paid_amount' => $payment->amount,
                'paid_at' => now(),
            ]);

            $payment->update([
                'status' => 'SUCCESS',
                'paid_at' => now(),
            ]);

            $customer = $invoice->customer;
            $previousStatus = $customer->status;

            $customer->update([
                'status' => 'ACTIVE',
            ]);

            AuditLog::create([
                'tenant_id' => $invoice->tenant_id,
                'auditable_type' => Invoice::class,
                'auditable_id' => $invoice->id,
                'event' => 'PAYMENT_RECEIVED',
                'description' => "Pembayaran tagihan {$invoice->invoice_number} sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " telah lunas.",
            ]);

            // Auto Restore MikroTik if router is configured
            if ($customer->router) {
                $this->mikrotikService->restoreCustomer($customer->router, $customer, $customer->package);
            }

            // Send WhatsApp payment confirmation
            $message = "Terima kasih {$customer->name}, pembayaran tagihan internet nomor {$invoice->invoice_number} sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " telah kami terima. Layanan internet Anda telah aktif.";
            $this->whatsAppService->sendToCustomer($customer->tenant, $customer, $message, $invoice);
        });
    }

    /**
     * Auto Cut: Isolate customer when grace period expires
     */
    public function isolateCustomer(Customer $customer): bool
    {
        if (!$customer->auto_cut_enabled || $customer->status === 'ISOLATED') {
            return false;
        }

        $router = $customer->router;
        if ($router) {
            $this->mikrotikService->isolateCustomer($router, $customer);
        }

        $customer->update([
            'status' => 'ISOLATED',
        ]);

        AuditLog::create([
            'tenant_id' => $customer->tenant_id,
            'auditable_type' => Customer::class,
            'auditable_id' => $customer->id,
            'event' => 'CUSTOMER_ISOLATED',
            'description' => "Pelanggan {$customer->name} ({$customer->customer_code}) diisolir otomatis karena melewati batas toleransi jatuh tempo.",
        ]);

        // Send WhatsApp isolation warning
        $latestUnpaid = $customer->invoices()->whereIn('status', ['UNPAID', 'OVERDUE'])->latest()->first();
        $payUrl = $latestUnpaid ? $latestUnpaid->payment_url : url('/');
        $message = "Yth. {$customer->name}, akses internet Anda sementara ditangguhkan karena tagihan telah melewati batas jatuh tempo. Silakan lakukan pembayaran melalui tautan berikut untuk mengaktifkan kembali: {$payUrl}";

        $this->whatsAppService->sendToCustomer($customer->tenant, $customer, $message, $latestUnpaid);

        return true;
    }
}
