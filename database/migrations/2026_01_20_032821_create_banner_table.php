<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->increments('banner_id'); // int(10) unsigned AUTO_INCREMENT
            $table->string('banner_title', 255)->nullable();
            $table->string('banner_image', 255)->nullable();
            $table->string('banner_icon', 255)->nullable();
            $table->string('banner_text', 255)->nullable();
            $table->text('banner_desc')->nullable();
            $table->unsignedBigInteger('banner_order')->nullable();
            $table->enum('banner_status', ['0', '1'])->default('0');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
