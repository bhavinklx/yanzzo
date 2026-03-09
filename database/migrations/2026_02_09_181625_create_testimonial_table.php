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
        Schema::create('testimonial', function (Blueprint $table) {
            $table->bigIncrements('testimonial_id');
            $table->string('testimonial_title')->nullable();
            $table->string('testimonial_designation')->nullable();
            $table->string('testimonial_image')->nullable();
            $table->text('testimonial_desc')->nullable();
            $table->integer('testimonial_order')->nullable();
            $table->boolean('testimonial_status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial');
    }
};
