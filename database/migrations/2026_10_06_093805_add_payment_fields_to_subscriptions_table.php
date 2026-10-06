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
            if (!Schema::hasColumn('subscriptions', 'order_number')) {
                $table->string('order_number', 40)->nullable()->after('saas_plan_id');
            }
            if (!Schema::hasColumn('subscriptions', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('subscriptions', 'billing_cycle')) {
                $table->string('billing_cycle', 20)->default('monthly');
            }
            if (!Schema::hasColumn('subscriptions', 'payment_method')) {
                $table->string('payment_method', 150)->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'proof_path')) {
                $table->string('proof_path', 255)->nullable();
            }
            if (!Schema::hasColumn('subscriptions', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
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
