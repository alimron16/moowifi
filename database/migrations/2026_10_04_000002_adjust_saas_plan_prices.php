<?php

use App\Models\SaasPlan;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $adjustments = [
            'FREE' => ['price' => 0],
            'STANDAR' => ['price' => 79000],
            'PRO' => ['price' => 149000],
            'BISNIS' => ['price' => 249000],
            'ENTERPRISE' => ['price' => 399000],
        ];

        foreach ($adjustments as $code => $data) {
            SaasPlan::where('code', $code)->update($data);
        }
    }

    public function down(): void
    {
    }
};
