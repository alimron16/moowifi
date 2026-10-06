<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Router;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'ACTIVE')->count();
        $unpaidCustomers = Customer::where('status', 'UNPAID')->count();
        $isolatedCustomers = Customer::where('status', 'ISOLATED')->count();

        // Financial metrics this month
        $revenueThisMonth = Payment::where('status', 'SUCCESS')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $receivables = Invoice::whereIn('status', ['UNPAID', 'OVERDUE'])
            ->sum('total_amount');

        // System and operational stats
        $pendingManualPayments = Payment::where('status', 'WAITING_VERIFICATION')->count();
        $dueTodayCount = Invoice::where('due_date', now()->toDateString())->where('status', 'UNPAID')->count();
        $overdueInvoices = Invoice::with('customer')->where('status', 'OVERDUE')->latest()->take(5)->get();
        $recentPayments = Payment::with(['customer', 'invoice'])->where('status', 'SUCCESS')->latest()->take(5)->get();

        // 6 months billing trend
        $chartMonths = [];
        $chartRevenue = [];
        $chartInvoiced = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            $chartMonths[] = $monthName;

            $rev = Payment::where('status', 'SUCCESS')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
            $chartRevenue[] = (float) $rev;

            $inv = Invoice::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            $chartInvoiced[] = (float) $inv;
        }

        $routers = Router::latest()->take(5)->get();
        $recentInvoices = Invoice::with('customer')->latest()->take(6)->get();

        // Status configurations with safe fallback
        $waType = $tenant?->getSetting('whatsapp.type', 'API') ?? 'API';
        $waConnected = $waType === 'QR' 
            ? ($tenant?->getSetting('whatsapp.qr_status') === 'CONNECTED')
            : !empty($tenant?->getSetting('whatsapp.token'));
        
        $autocutConfig = [
            'enabled' => $tenant ? $tenant->getSetting('autocut.enabled', true) : true,
            'grace_period' => $tenant ? $tenant->getSetting('autocut.grace_days', 3) : 3,
            'isolation_time' => $tenant ? $tenant->getSetting('autocut.time', '00:00') : '00:00',
        ];

        // SaaS Plan & Quota Management
        $activePlan = $tenant?->activePlan();
        $planName = $activePlan ? $activePlan->name : ($tenant?->plan ?? 'Standar');
        $maxCustomers = $tenant ? $tenant->getMaxCustomers() : 150;
        $maxRouters = $tenant ? $tenant->getMaxRouters() : 1;
        $totalRouters = Router::count();
        $customerUsagePercent = min(100, round(($totalCustomers / max(1, $maxCustomers)) * 100));
        $routerUsagePercent = min(100, round(($totalRouters / max(1, $maxRouters)) * 100));
        $isExpired = $tenant ? $tenant->isExpired() : false;
        $isTrial = $tenant ? $tenant->isTrial() : false;
        $trialDaysLeft = ($tenant && $tenant->trial_ends_at) ? max(0, (int) now()->diffInDays($tenant->trial_ends_at, false)) : 0;
        $subscriptionEndsAt = $tenant?->currentSubscription?->ends_at ?? $tenant?->trial_ends_at;

        return view('tenant.dashboard', compact(
            'tenant',
            'activePlan',
            'planName',
            'maxCustomers',
            'maxRouters',
            'totalRouters',
            'customerUsagePercent',
            'routerUsagePercent',
            'isExpired',
            'isTrial',
            'trialDaysLeft',
            'subscriptionEndsAt',
            'totalCustomers',
            'activeCustomers',
            'unpaidCustomers',
            'isolatedCustomers',
            'revenueThisMonth',
            'receivables',
            'routers',
            'recentInvoices',
            'recentPayments',
            'overdueInvoices',
            'pendingManualPayments',
            'dueTodayCount',
            'chartMonths',
            'chartRevenue',
            'chartInvoiced',
            'waConnected',
            'waType',
            'autocutConfig'
        ));
    }
}
