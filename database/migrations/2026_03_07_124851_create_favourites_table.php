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
        Schema::create('favourite', function (Blueprint $table) {
            $table->increments('favourite_id'); // Primary key with prefix
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id'); // Using user_id as requested, will store customer_id
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourite');
    }
};
