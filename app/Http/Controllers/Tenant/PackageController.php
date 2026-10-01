<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('customers')->latest()->get();
        return view('tenant.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'download_speed' => ['required', 'string', 'max:20'],
            'upload_speed' => ['required', 'string', 'max:20'],
            'mikrotik_profile' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ]);

        Package::create($validated);

        return back()->with('success', 'Paket internet baru berhasil ditambahkan.');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'download_speed' => ['required', 'string', 'max:20'],
            'upload_speed' => ['required', 'string', 'max:20'],
            'mikrotik_profile' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
            'description' => ['nullable', 'string'],
        ]);

        $package->update($validated);

        return back()->with('success', 'Paket internet berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->customers()->count() > 0) {
            return back()->with('error', 'Paket tidak dapat dihapus karena masih digunakan oleh pelanggan aktif.');
        }

        $package->delete();

        return back()->with('success', 'Paket internet berhasil dihapus.');
    }
}
