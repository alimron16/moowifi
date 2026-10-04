<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\ManualPaymentConfirmation;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\Payment\PaymentGatewayManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PaymentPageController extends Controller
{
    public function __construct(protected PaymentGatewayManager $gatewayManager)
    {
    }

    public function show(string $token)
    {
        $invoice = Invoice::withoutGlobalScopes()
            ->with(['customer', 'items', 'customer.package', 'tenant'])
            ->where('payment_token', $token)
            ->firstOrFail();

        $tenant = $invoice->tenant;

        $paymentMethods = PaymentMethod::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $pendingConfirmation = ManualPaymentConfirmation::withoutGlobalScopes()
            ->where('invoice_id', $invoice->id)
            ->where('status', 'WAITING_VERIFICATION')
            ->latest()
            ->first();

        return view('public.payment', compact('invoice', 'tenant', 'paymentMethods', 'pendingConfirmation'));
    }

    public function payGateway(Request $request, string $token)
    {
        $throttleKey = 'pay-gateway:' . $request->ip() . '|' . $token;
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak permintaan pembayaran. Silakan tunggu {$seconds} detik.");
        }
        RateLimiter::hit($throttleKey, 60);

        $invoice = Invoice::withoutGlobalScopes()
            ->with(['customer', 'items', 'tenant'])
            ->where('payment_token', $token)
            ->firstOrFail();

        if ($invoice->isPaid()) {
            return back()->with('info', 'Tagihan ini sudah lunas.');
        }

        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'payment_channel' => ['nullable', 'string', 'max:50'],
        ]);

        $method = PaymentMethod::withoutGlobalScopes()->findOrFail($request->payment_method_id);

        if (!$method->isGateway()) {
            return back()->with('error', 'Metode pembayaran bukan gateway.');
        }

        $gateway = $this->gatewayManager->driver($method->provider);
        $response = $gateway->createPayment($invoice, $method, [
            'payment_channel' => $request->payment_channel,
        ]);

        if ($response->success && $response->paymentUrl) {
            return redirect()->away($response->paymentUrl);
        }

        return back()->with('error', $response->errorMessage ?? 'Gagal memproses pembayaran ke gateway.');
    }

    public function submitManualTransfer(Request $request, string $token)
    {
        $throttleKey = 'submit-manual-transfer:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->with('error', "Terlalu banyak unggahan bukti transfer dari perangkat Anda. Silakan coba lagi dalam {$seconds} detik.");
        }
        RateLimiter::hit($throttleKey, 300);

        $invoice = Invoice::withoutGlobalScopes()
            ->with('customer')
            ->where('payment_token', $token)
            ->firstOrFail();

        if ($invoice->isPaid()) {
            return back()->with('info', 'Tagihan ini sudah berstatus lunas.');
        }

        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'sender_name' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:50'],
            'transfer_amount' => ['required', 'numeric', 'min:1'],
            'transfer_date' => ['required', 'date'],
            'proof_image' => ['required', 'file', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // Max 5MB strictly image
        ]);

        $path = $request->file('proof_image')->store('payment-proofs', 'public');

        DB::transaction(function () use ($invoice, $request, $path) {
            $payment = Payment::create([
                'tenant_id' => $invoice->tenant_id,
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'payment_method_id' => $request->payment_method_id,
                'payment_code' => 'PAY-MANUAL-' . strtoupper(Str::random(8)),
                'amount' => $request->transfer_amount,
                'status' => 'WAITING_VERIFICATION',
                'notes' => 'Konfirmasi transfer mandiri oleh pelanggan.',
            ]);

            ManualPaymentConfirmation::create([
                'tenant_id' => $invoice->tenant_id,
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'sender_name' => $request->sender_name,
                'transfer_amount' => $request->transfer_amount,
                'transfer_date' => $request->transfer_date,
                'proof_image_path' => $path,
                'status' => 'WAITING_VERIFICATION',
            ]);
        });

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Admin akan segera memverifikasi mutasi bank Anda.');
    }
}
