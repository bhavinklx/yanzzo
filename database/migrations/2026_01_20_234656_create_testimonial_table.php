<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonial', function (Blueprint $table) {
            $table->increments('testimonial_id'); // int(10) UNSIGNED AUTO_INCREMENT
            $table->string('testimonial_title', 255)->nullable();
            $table->string('testimonial_designation', 255)->nullable();
            $table->string('testimonial_image', 255)->nullable();
            $table->text('testimonial_desc')->nullable();
            $table->unsignedBigInteger('testimonial_order')->nullable();
            $table->enum('testimonial_status', ['0', '1'])
                ->default('0');

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonial');
    }
};
