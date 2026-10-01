<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\WebhookLog;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('status', 'ACTIVE')->count();
        $trialTenants = Tenant::where('status', 'TRIAL')->count();
        $suspendedTenants = Tenant::where('status', 'SUSPENDED')->count();
        $newTenantsThisMonth = Tenant::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalCustomersPlatform = Customer::withoutGlobalScopes()->count();
        $totalRevenuePlatform = Payment::withoutGlobalScopes()->where('status', 'SUCCESS')->sum('amount');

        // SaaS Subscription Metrics
        $totalSubscriptions = \App\Models\Subscription::count();
        $subscriptionRevenue = \App\Models\Subscription::with('saasPlan')
            ->where('status', 'ACTIVE')
            ->get()
            ->sum(fn($sub) => $sub->saasPlan?->price ?? 0);
        $mrr = $subscriptionRevenue; // Monthly Recurring Revenue
        $overdueSubscriptions = \App\Models\Subscription::where('status', 'EXPIRED')->count();

        // System Health & Diagnostics
        $failedWebhooks = WebhookLog::where('status', 'FAILED')->count();
        $failedNotifications = \App\Models\NotificationLog::where('status', 'FAILED')->count();
        $routerErrors = \App\Models\Router::withoutGlobalScopes()->where('status', 'OFFLINE')->count();
        $systemHealth = ($failedWebhooks === 0 && $routerErrors === 0) ? 'OPTIMAL' : 'PERHATIAN';

        $recentTenants = Tenant::with('users')->latest()->take(6)->get();
        $recentWebhooks = WebhookLog::latest()->take(6)->get();

        return view('super-admin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'trialTenants',
            'suspendedTenants',
            'newTenantsThisMonth',
            'totalCustomersPlatform',
            'totalRevenuePlatform',
            'totalSubscriptions',
            'subscriptionRevenue',
            'mrr',
            'overdueSubscriptions',
            'failedWebhooks',
            'failedNotifications',
            'routerErrors',
            'systemHealth',
            'recentTenants',
            'recentWebhooks'
        ));
    }
}
