<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Router;
use App\Models\WebhookLog;

class SystemMonitoringController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $tab = $request->get('tab', 'health');

        // Routers Health
        $routers = Router::withoutGlobalScopes()->with('tenant')->latest()->paginate(15, ['*'], 'routers_page');
        $totalRouters = Router::withoutGlobalScopes()->count();
        $onlineRouters = Router::withoutGlobalScopes()->where('status', 'ONLINE')->count();
        $offlineRouters = Router::withoutGlobalScopes()->where('status', 'OFFLINE')->count();

        // Queue & Background Jobs
        $queueJobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
        $failedJobsCount = \Illuminate\Support\Facades\DB::table('failed_jobs')->count();

        // Webhook Activity
        $webhooks = WebhookLog::latest()->paginate(15, ['*'], 'webhooks_page');

        // System Audit Logs
        $auditLogs = AuditLog::with('user')->latest()->paginate(20, ['*'], 'logs_page');

        // Scheduler Tasks
        $scheduledTasks = [
            ['command' => 'billing:generate-monthly', 'cron' => '00:01 WIB', 'interval' => 'Harian', 'purpose' => 'Auto-generate invoice bulanan pelanggan'],
            ['command' => 'billing:check-due', 'cron' => '00:05 WIB', 'interval' => 'Harian', 'purpose' => 'Perbarui invoice UNPAID lewat tanggal jatuh tempo jadi OVERDUE'],
            ['command' => 'billing:auto-cut', 'cron' => '00:15 WIB', 'interval' => 'Harian', 'purpose' => 'Auto-cut isolir MikroTik pelanggan lewati masa toleransi'],
            ['command' => 'billing:send-reminders', 'cron' => '09:00 WIB', 'interval' => 'Harian', 'purpose' => 'Kirim reminder H-3, H-1, & Due Date via WhatsApp'],
            ['command' => 'mikrotik:ping', 'cron' => '*/5 Menit', 'interval' => 'Setiap 5 Menit', 'purpose' => 'Heartbeat konektivitas seluruh router MikroTik tenant'],
        ];

        return view('super-admin.monitoring.index', compact(
            'tab',
            'routers',
            'totalRouters',
            'onlineRouters',
            'offlineRouters',
            'queueJobsCount',
            'failedJobsCount',
            'webhooks',
            'auditLogs',
            'scheduledTasks'
        ));
    }
}
