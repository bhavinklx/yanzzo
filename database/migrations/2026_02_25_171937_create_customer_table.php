<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('customer_id');
            $table->string('customer_name');
            $table->string('customer_image')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_mobile')->nullable();
            $table->string('customer_password');
            $table->string('customer_created_ip')->nullable();
            $table->timestamp('customer_last_login_date')->nullable();
            $table->string('customer_last_login_ip')->nullable();
            $table->string('customer_otp')->nullable();
            $table->integer('customer_order')->default(0);
            $table->enum('customer_status', ['0', '1'])->default('0')->index();
            $table->enum('customer_type', ['0', '1', '2'])->default('0')->index();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};