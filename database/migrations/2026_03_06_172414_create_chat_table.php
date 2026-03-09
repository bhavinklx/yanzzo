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
        Schema::create('chat', function (Blueprint $table) {
            $table->increments('chat_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('receiver_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('sender_id')->references('customer_id')->on('customer')->onDelete('cascade');
            $table->foreign('receiver_id')->references('customer_id')->on('customer')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('product')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat');
    }
};
