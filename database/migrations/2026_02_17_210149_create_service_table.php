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
        Schema::create('service', function (Blueprint $table) {
            $table->increments('service_id');
            $table->string('service_title')->nullable();
            $table->string('service_slug')->nullable();
            $table->string('service_image')->nullable();
            $table->text('service_desc')->nullable();
            $table->string('service_meta_title')->nullable();
            $table->text('service_meta_keyword')->nullable();
            $table->text('service_meta_desc')->nullable();
            $table->integer('service_order')->nullable();
            $table->enum('service_status', ['0', '1'])->default('0')->index();
            $table->enum('service_type', ['0', '1'])->default('0')->index();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
