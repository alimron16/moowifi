<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->string('download_speed', 20)->default('10M');
            $table->string('upload_speed', 20)->default('5M');
            $table->string('mikrotik_profile')->nullable();
            $table->integer('billing_cycle_days')->default(30);
            $table->text('description')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->enum('connection_type', ['DIRECT', 'VPN_TUNNEL'])->default('DIRECT');
            $table->string('host')->nullable();
            $table->integer('port')->default(8728);
            $table->string('username')->nullable();
            $table->text('encrypted_password')->nullable();
            $table->string('vpn_user', 50)->nullable();
            $table->text('encrypted_vpn_password')->nullable();
            $table->string('tunnel_ip', 45)->nullable();
            $table->boolean('use_ssl')->default(false);
            $table->enum('status', ['ONLINE', 'OFFLINE', 'UNVERIFIED'])->default('UNVERIFIED');
            $table->timestamp('last_seen_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->string('customer_code', 30);
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('id_card_number', 50)->nullable();
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('connection_type', ['PPPOE', 'HOTSPOT', 'STATIC_IP'])->default('PPPOE');
            $table->string('mikrotik_username')->nullable();
            $table->text('encrypted_mikrotik_password')->nullable();
            $table->string('static_ip', 45)->nullable();
            $table->enum('billing_type', ['PREPAID', 'POSTPAID'])->default('PREPAID');
            $table->enum('billing_cycle_type', ['CALENDAR_MONTH', 'ANNIVERSARY'])->default('CALENDAR_MONTH');
            $table->integer('billing_day')->default(1);
            $table->integer('due_day')->default(10);
            $table->integer('grace_period_days')->default(3);
            $table->enum('status', ['ACTIVE', 'UNPAID', 'OVERDUE', 'ISOLATED', 'SUSPENDED', 'INACTIVE'])->default('ACTIVE');
            $table->boolean('auto_cut_enabled')->default(true);
            $table->date('installation_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'customer_code']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'router_id']);
            $table->index(['tenant_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('routers');
        Schema::dropIfExists('packages');
    }
};
