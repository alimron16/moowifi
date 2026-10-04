<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Router;
use App\Models\SaasPlan;
use App\Models\SupportTicket;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::where('is_active', true)->get();

        $stats = [
            'tenants' => max(Tenant::count(), 85),
            'customers' => max(Customer::count(), 6420),
            'routers' => max(Router::count(), 194),
            'invoices' => max(Invoice::where('status', 'PAID')->count(), 28500),
        ];

        return view('landing.index', compact('plans', 'stats'));
    }

    public function storeTicket(Request $request)
    {
        $throttleKey = 'support-ticket-store:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect(url()->previous() . '#support-ticket')
                ->with('ticket_error', "Terlalu banyak permintaan pengiriman tiket dari perangkat Anda. Silakan coba lagi dalam {$seconds} detik.");
        }
        RateLimiter::hit($throttleKey, 300); // 5 tickets per 5 minutes max

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'category' => ['required', 'in:GENERAL,MIKROTIK,BILLING,ACCOUNT'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi untuk pemberitahuan balasan.',
            'subject.required' => 'Subjek kendala wajib diisi.',
            'message.required' => 'Pesan kendala wajib diisi secara rinci.',
        ]);

        $ticketNumber = 'TKT-' . date('ym') . '-' . strtoupper(Str::random(5));

        $ticket = SupportTicket::create([
            'ticket_number' => $ticketNumber,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'OPEN',
            'priority' => 'MEDIUM',
        ]);

        return redirect(url()->previous() . '#support-ticket')
            ->with('ticket_created', [
                'number' => $ticket->ticket_number,
                'email' => $ticket->email,
                'subject' => $ticket->subject,
                'category' => $ticket->category,
            ])
            ->with('success', "Tiket bantuan berhasil dikirim! Simpan Nomor Tiket Anda: {$ticket->ticket_number}");
    }

    public function checkTicket(Request $request)
    {
        $throttleKey = 'support-ticket-check:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 10)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect(url()->previous() . '#support-ticket')
                ->with('ticket_error', "Terlalu banyak permintaan pengecekan tiket. Silakan coba lagi dalam {$seconds} detik.");
        }
        RateLimiter::hit($throttleKey, 60); // 10 attempts per minute max

        $validated = $request->validate([
            'ticket_number' => ['required', 'string'],
            'email' => ['required', 'email'],
        ], [
            'ticket_number.required' => 'Nomor tiket wajib diisi.',
            'email.required' => 'Email pendaftar tiket wajib diisi.',
        ]);

        $ticket = SupportTicket::where('ticket_number', trim(strtoupper($validated['ticket_number'])))
            ->where('email', trim($validated['email']))
            ->first();

        if (!$ticket) {
            return redirect(url()->previous() . '#support-ticket')
                ->with('ticket_error', 'Tiket bantuan tidak ditemukan. Pastikan Nomor Tiket dan Email yang dimasukkan sesuai.');
        }

        return redirect(url()->previous() . '#support-ticket')
            ->with('ticket_found', $ticket);
    }

    public function sitemap()
    {
        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => now()->startOfDay()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => url('/register'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/login'),
                'lastmod' => now()->startOfMonth()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
        ];

        $xml = view('landing.sitemap', compact('urls'))->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
