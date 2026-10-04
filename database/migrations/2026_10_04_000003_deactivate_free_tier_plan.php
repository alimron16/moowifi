<?php

use App\Models\SaasPlan;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        SaasPlan::where('code', 'FREE')->update(['is_active' => false]);
    }

    public function down(): void
    {
        SaasPlan::where('code', 'FREE')->update(['is_active' => true]);
    }
};
