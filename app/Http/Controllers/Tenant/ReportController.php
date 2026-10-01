<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'revenue');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // 1. Revenue Data
        $totalRevenueYear = Payment::where('status', 'SUCCESS')
            ->whereYear('paid_at', $year)
            ->sum('amount');

        $monthlyRevenue = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[$m] = Payment::where('status', 'SUCCESS')
                ->whereYear('paid_at', $year)
                ->whereMonth('paid_at', $m)
                ->sum('amount');
        }

        // 2. Receivables Data
        $totalReceivables = Invoice::whereIn('status', ['UNPAID', 'OVERDUE'])
            ->sum('total_amount');
        $overdueInvoices = Invoice::with(['customer', 'customer.package'])
            ->whereIn('status', ['UNPAID', 'OVERDUE'])
            ->latest('due_date')
            ->paginate(15, ['*'], 'receivables_page');

        // 3. Customers Data
        $totalCustomers = \App\Models\Customer::count();
        $activeCustomers = \App\Models\Customer::where('status', 'ACTIVE')->count();
        $unpaidCustomers = \App\Models\Customer::where('status', 'UNPAID')->count();
        $isolatedCustomers = \App\Models\Customer::where('status', 'ISOLATED')->count();
        $suspendedCustomers = \App\Models\Customer::where('status', 'SUSPENDED')->count();

        // 4. Payments Methods Data
        $paymentMethodsStats = Payment::with('paymentMethod')
            ->where('status', 'SUCCESS')
            ->selectRaw('payment_method_id, count(*) as count, sum(amount) as total')
            ->groupBy('payment_method_id')
            ->get();

        $recentPayments = Payment::with(['invoice', 'customer', 'paymentMethod'])
            ->where('status', 'SUCCESS')
            ->latest('paid_at')
            ->paginate(15, ['*'], 'payments_page');

        // 5. Monthly Report Data
        $selectedPeriodInvoices = Invoice::with(['customer', 'payments'])
            ->whereYear('period_start', $year)
            ->whereMonth('period_start', $month)
            ->paginate(20, ['*'], 'monthly_page');

        $periodTotalBilled = Invoice::whereYear('period_start', $year)
            ->whereMonth('period_start', $month)
            ->sum('total_amount');

        $periodTotalCollected = Payment::where('status', 'SUCCESS')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $month)
            ->sum('amount');

        $paidInvoicesCount = Invoice::where('status', 'PAID')->count();
        $unpaidInvoicesCount = Invoice::whereIn('status', ['UNPAID', 'OVERDUE'])->count();

        return view('tenant.reports.index', compact(
            'tab',
            'year',
            'month',
            'totalRevenueYear',
            'monthlyRevenue',
            'totalReceivables',
            'overdueInvoices',
            'totalCustomers',
            'activeCustomers',
            'unpaidCustomers',
            'isolatedCustomers',
            'suspendedCustomers',
            'paymentMethodsStats',
            'recentPayments',
            'selectedPeriodInvoices',
            'periodTotalBilled',
            'periodTotalCollected',
            'paidInvoicesCount',
            'unpaidInvoicesCount'
        ));
    }
}
