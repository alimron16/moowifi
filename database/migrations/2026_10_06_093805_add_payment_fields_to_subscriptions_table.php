<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('order_number', 40)->nullable()->after('saas_plan_id');
            $table->decimal('amount', 12, 2)->default(0)->after('order_number');
            $table->string('billing_cycle', 20)->default('monthly')->after('amount');
            $table->string('payment_method', 50)->nullable()->after('billing_cycle');
            $table->string('proof_path', 255)->nullable()->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('ends_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'amount',
                'billing_cycle',
                'payment_method',
                'proof_path',
                'paid_at',
            ]);
        });
    }
};
