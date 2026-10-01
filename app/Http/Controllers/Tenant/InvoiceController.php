<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\BillingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(protected BillingService $billingService)
    {
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'customer.package'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('due_today')) {
            $query->whereDate('due_date', now()->toDateString())
                  ->whereIn('status', ['UNPAID', 'OVERDUE']);
        }

        if ($request->filled('month')) {
            $date = Carbon::parse($request->month);
            $query->whereYear('period_start', $date->year)
                  ->whereMonth('period_start', $date->month);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%{$s}%")
                  ->orWhereHas('customer', function ($cq) use ($s) {
                      $cq->where('name', 'like', "%{$s}%")
                         ->orWhere('customer_code', 'like', "%{$s}%");
                  });
            });
        }

        $invoices = $query->paginate(15)->withQueryString();

        return view('tenant.invoices.index', compact('invoices'));
    }

    public function payments(Request $request)
    {
        $payments = Payment::with(['invoice', 'customer', 'paymentMethod'])
            ->latest('paid_at')
            ->paginate(15);

        return view('tenant.invoices.payments', compact('payments'));
    }

    public function create()
    {
        $customers = Customer::where('status', '!=', 'INACTIVE')->get();
        return view('tenant.invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $mode = $request->input('mode', 'single');

        if ($mode === 'single') {
            $request->validate([
                'customer_id' => ['required', 'exists:customers,id'],
                'due_date' => ['required', 'date'],
                'period_start' => ['required', 'date'],
                'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            ]);

            $customer = Customer::findOrFail($request->customer_id);
            $invoice = $this->billingService->generateInvoice(
                $customer,
                Carbon::parse($request->period_start),
                Carbon::parse($request->period_end),
                Carbon::parse($request->due_date)
            );

            return redirect()->route('tenant.invoices.show', $invoice->id)->with('success', 'Tagihan berhasil dibuat.');
        } else {
            // Batch generation for all active customers for selected month
            $request->validate([
                'billing_month' => ['required', 'date_format:Y-m'],
                'due_day' => ['required', 'integer', 'min:1', 'max:28'],
            ]);

            $monthDate = Carbon::parse($request->billing_month . '-01');
            $periodStart = $monthDate->copy()->startOfMonth();
            $periodEnd = $monthDate->copy()->endOfMonth();
            $dueDate = $monthDate->copy()->day((int)$request->due_day);

            $customers = Customer::whereIn('status', ['ACTIVE', 'UNPAID', 'OVERDUE', 'ISOLATED'])->get();
            $generated = 0;

            foreach ($customers as $c) {
                // Check if invoice already exists for this customer and period
                $exists = Invoice::where('customer_id', $c->id)
                    ->whereYear('period_start', $periodStart->year)
                    ->whereMonth('period_start', $periodStart->month)
                    ->exists();

                if (!$exists) {
                    $this->billingService->generateInvoice($c, $periodStart, $periodEnd, $dueDate);
                    $generated++;
                }
            }

            return redirect()->route('tenant.invoices.index')->with('success', "Berhasil menerbitkan {$generated} tagihan baru untuk periode " . $monthDate->translatedFormat('F Y'));
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'payments', 'payments.paymentMethod']);
        $tenant = Auth::user()->tenant;

        return view('tenant.invoices.show', compact('invoice', 'tenant'));
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        if ($invoice->isPaid()) {
            return back()->with('info', 'Tagihan ini sudah berstatus lunas.');
        }

        $payment = Payment::create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'payment_code' => 'PAY-MANUAL-' . strtoupper(uniqid()),
            'amount' => $invoice->total_amount,
            'status' => 'PENDING',
            'verified_by' => Auth::id(),
            'notes' => $request->input('notes', 'Pelunasan manual oleh admin/kasir'),
        ]);

        $this->billingService->markInvoicePaid($invoice, $payment);

        return back()->with('success', "Tagihan {$invoice->invoice_number} telah ditandai lunas dan layanan internet diaktifkan.");
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);
        $tenant = Auth::user()->tenant;

        $pdf = Pdf::loadView('tenant.invoices.pdf', compact('invoice', 'tenant'));
        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    public function sendEmail(Invoice $invoice, \App\Services\EmailService $emailService)
    {
        $result = $emailService->sendInvoice($invoice);
        if ($result['success']) {
            return back()->with('success', 'Invoice PDF berhasil dikirim ke email pelanggan via Gmail Anda.');
        }
        return back()->with('error', $result['message']);
    }

    public function sendWhatsApp(Invoice $invoice, \App\Services\WhatsApp\WhatsAppService $waService)
    {
        $tenant = $invoice->tenant;
        $customer = $invoice->customer;

        if (empty($customer->phone)) {
            return back()->with('error', 'Pelanggan tidak memiliki nomor HP/WhatsApp.');
        }

        $tpl = $tenant->getSetting('templates.wa_invoice', "Halo {customer_name}, tagihan internet {business_name} nomor {invoice_number} sebesar {amount} telah terbit. Jatuh tempo: {due_date}. Bayar di: {payment_link}");

        $message = str_replace([
            '{customer_name}',
            '{invoice_number}',
            '{amount}',
            '{due_date}',
            '{payment_link}',
            '{business_name}',
        ], [
            $customer->name,
            $invoice->invoice_number,
            'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
            $invoice->due_date->format('d/m/Y'),
            $invoice->payment_url,
            $tenant->name,
        ], $tpl);

        $result = $waService->sendMessage($tenant, $customer->phone, $message);

        \App\Models\NotificationLog::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'invoice_id' => $invoice->id,
            'channel' => 'WHATSAPP',
            'recipient' => $customer->phone,
            'subject' => 'Tagihan Internet ' . $invoice->invoice_number,
            'content' => $message,
            'status' => $result['success'] ? 'SENT' : 'FAILED',
            'error_message' => $result['success'] ? null : $result['message'],
            'sent_at' => $result['success'] ? now() : null,
        ]);

        if ($result['success']) {
            return back()->with('success', 'Notifikasi WhatsApp tagihan berhasil dikirim ke ' . $customer->phone);
        }
        return back()->with('error', $result['message']);
    }
}
