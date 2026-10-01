<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PlatformSettingsController extends Controller
{
    public function settings(Request $request)
    {
        return view('super-admin.settings.platform');
    }

    public function paymentProviders()
    {
        return view('super-admin.settings.payment-providers');
    }

    public function whatsapp()
    {
        return view('super-admin.settings.whatsapp');
    }

    public function email()
    {
        return view('super-admin.settings.email');
    }

    public function logs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(25);
        return view('super-admin.settings.logs', compact('logs'));
    }

    public function announcements()
    {
        return view('super-admin.settings.announcements');
    }

    public function support()
    {
        return view('super-admin.settings.support');
    }
}
