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
        Schema::create('contact', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_country')->nullable();
            $table->string('contact_prefix',10)->nullable();
            $table->string('contact_mobile',20)->nullable();
            $table->string('contact_city')->nullable();
            $table->string('contact_zipcode',20)->nullable();
            $table->string('contact_subject')->nullable();
            $table->text('contact_message')->nullable();
            $table->string('contact_ip',45)->nullable();
            $table->string('contact_order')->nullable();
            $table->tinyInteger('contact_status')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
