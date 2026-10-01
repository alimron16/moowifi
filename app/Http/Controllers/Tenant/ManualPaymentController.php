<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ManualPaymentConfirmation;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualPaymentController extends Controller
{
    public function __construct(protected BillingService $billingService)
    {
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'WAITING_VERIFICATION');

        $confirmations = ManualPaymentConfirmation::with(['invoice', 'payment', 'invoice.customer'])
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        return view('tenant.manual-payments.index', compact('confirmations', 'status'));
    }

    public function approve(Request $request, ManualPaymentConfirmation $confirmation)
    {
        if ($confirmation->status !== 'WAITING_VERIFICATION') {
            return back()->with('error', 'Konfirmasi pembayaran ini sudah pernah diproses.');
        }

        $confirmation->update([
            'status' => 'APPROVED',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $invoice = $confirmation->invoice;
        $payment = $confirmation->payment;

        $this->billingService->markInvoicePaid($invoice, $payment);

        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => ManualPaymentConfirmation::class,
            'auditable_id' => $confirmation->id,
            'event' => 'PAYMENT_APPROVED',
            'description' => "Menyetujui transfer manual tagihan {$invoice->invoice_number} dari {$confirmation->sender_name}.",
        ]);

        return back()->with('success', "Pembayaran tagihan {$invoice->invoice_number} telah disetujui. Layanan internet pelanggan aktif kembali.");
    }

    public function reject(Request $request, ManualPaymentConfirmation $confirmation)
    {
        if ($confirmation->status !== 'WAITING_VERIFICATION') {
            return back()->with('error', 'Konfirmasi pembayaran ini sudah pernah diproses.');
        }

        $reason = $request->input('rejection_reason', 'Mutasi transfer tidak ditemukan pada rekening bank.');

        $confirmation->update([
            'status' => 'REJECTED',
            'rejection_reason' => $reason,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $confirmation->payment->update([
            'status' => 'FAILED',
            'notes' => 'Ditolak: ' . $reason,
        ]);

        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => ManualPaymentConfirmation::class,
            'auditable_id' => $confirmation->id,
            'event' => 'PAYMENT_REJECTED',
            'description' => "Menolak bukti transfer tagihan {$confirmation->invoice->invoice_number}. Alasan: {$reason}",
        ]);

        return back()->with('warning', "Pembayaran ditolak. Tagihan tetap dalam status belum lunas.");
    }
}
