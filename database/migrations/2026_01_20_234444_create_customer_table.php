<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_name', 255)->nullable();
            $table->string('customer_image', 255)->nullable();
            $table->string('customer_email', 255)->nullable()->index();
            $table->string('customer_mobile', 255)->nullable()->index();
            $table->string('customer_password', 255)->nullable()->index();
            $table->string('customer_created_ip', 255)->nullable();
            $table->string('customer_last_login_date', 255)->nullable();
            $table->string('customer_last_login_ip', 255)->nullable();
            $table->string('customer_otp', 255)->nullable()->index();
            $table->integer('customer_order')->default(0);
            $table->enum('customer_status', ['0', '1'])
                ->default('0')
                ->comment('0=Inactive, 1=Active')
                ->index();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
