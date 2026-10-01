<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $manualMethods = PaymentMethod::where('type', 'MANUAL')->orderBy('sort_order')->get();
        return view('tenant.payment-methods.index', compact('manualMethods'));
    }

    public function gateway()
    {
        $gateways = PaymentMethod::where('type', 'GATEWAY')->get()->keyBy('provider');
        return view('tenant.payment-methods.gateway', compact('gateways'));
    }

    public function transactions(Request $request)
    {
        $transactions = \App\Models\PaymentTransaction::with(['payment', 'payment.invoice', 'payment.customer'])
            ->latest()
            ->paginate(15);

        return view('tenant.payments.transactions', compact('transactions'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:60'],
            'account_name' => ['required', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
        ]);

        $validated['type'] = 'MANUAL';
        $validated['is_active'] = true;

        PaymentMethod::create($validated);

        return back()->with('success', 'Rekening transfer manual baru berhasil ditambahkan.');
    }

    public function updateGateway(Request $request)
    {
        $provider = strtoupper($request->input('provider', 'DUITKU'));

        $rules = [
            'provider' => ['required', 'in:DUITKU,MIDTRANS,XENDIT,TRIPAY'],
            'environment' => ['required', 'in:sandbox,production'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($provider === 'DUITKU') {
            $rules['merchant_code'] = ['required', 'string', 'max:100'];
            $rules['api_key'] = ['required', 'string', 'max:150'];
        } elseif ($provider === 'MIDTRANS') {
            $rules['server_key'] = ['required', 'string', 'max:150'];
            $rules['client_key'] = ['nullable', 'string', 'max:150'];
        } elseif ($provider === 'XENDIT') {
            $rules['secret_key'] = ['required', 'string', 'max:150'];
            $rules['webhook_token'] = ['nullable', 'string', 'max:150'];
        } elseif ($provider === 'TRIPAY') {
            $rules['merchant_code'] = ['required', 'string', 'max:100'];
            $rules['api_key'] = ['required', 'string', 'max:150'];
            $rules['private_key'] = ['required', 'string', 'max:150'];
        }

        $request->validate($rules);

        $method = PaymentMethod::firstOrNew([
            'tenant_id' => Auth::user()->tenant_id,
            'type' => 'GATEWAY',
            'provider' => $provider,
        ]);

        $credentials = [
            'environment' => $request->environment,
        ];

        if ($provider === 'DUITKU') {
            $credentials['merchant_code'] = $request->merchant_code;
            $credentials['api_key'] = $request->api_key;
            $method->name = 'Duitku Gateway';
        } elseif ($provider === 'MIDTRANS') {
            $credentials['server_key'] = $request->server_key;
            $credentials['client_key'] = $request->client_key;
            $method->name = 'Midtrans Gateway (Snap)';
        } elseif ($provider === 'XENDIT') {
            $credentials['secret_key'] = $request->secret_key;
            $credentials['webhook_token'] = $request->webhook_token;
            $method->name = 'Xendit Gateway (Invoice)';
        } elseif ($provider === 'TRIPAY') {
            $credentials['merchant_code'] = $request->merchant_code;
            $credentials['api_key'] = $request->api_key;
            $credentials['private_key'] = $request->private_key;
            $method->name = 'Tripay Gateway';
        }

        $method->credentials = $credentials;
        $method->is_active = $request->boolean('is_active', true);
        $method->save();

        AuditLog::create([
            'tenant_id' => Auth::user()->tenant_id,
            'user_id' => Auth::id(),
            'auditable_type' => PaymentMethod::class,
            'auditable_id' => $method->id,
            'event' => 'GATEWAY_UPDATED',
            'description' => "Memperbarui konfigurasi payment gateway {$provider}.",
        ]);

        return back()->with('success', "Konfigurasi {$provider} Payment Gateway berhasil disimpan dan terenkripsi.");
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return back()->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}
