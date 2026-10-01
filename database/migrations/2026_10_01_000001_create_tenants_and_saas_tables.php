<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->enum('status', ['ACTIVE', 'TRIAL', 'SUSPENDED', 'EXPIRED'])->default('TRIAL');
            $table->timestamp('trial_ends_at')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });

        Schema::create('saas_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('max_customers')->default(100);
            $table->integer('max_routers')->default(1);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('saas_plan_id')->constrained('saas_plans')->cascadeOnDelete();
            $table->enum('status', ['ACTIVE', 'TRIAL', 'EXPIRED', 'CANCELLED'])->default('TRIAL');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            $table->string('phone', 30)->nullable()->after('email');
            $table->enum('role', ['SUPER_ADMIN', 'OWNER', 'ADMIN', 'FINANCE', 'TECHNICIAN'])->default('OWNER')->after('password');
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->after('role');
            $table->string('avatar_path')->nullable()->after('status');

            $table->index(['tenant_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'phone', 'role', 'status', 'avatar_path']);
        });
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('saas_plans');
        Schema::dropIfExists('tenants');
    }
};
