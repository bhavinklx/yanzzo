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
        Schema::create('category', function (Blueprint $table) {
            $table->increments('category_id');
            $table->unsignedBigInteger('category_parent')->nullable()->index();
            $table->string('category_title')->nullable();
            $table->string('category_slug')->nullable();
            $table->string('category_image')->nullable();
            $table->string('category_icon')->nullable();
            $table->text('category_desc')->nullable();
            $table->string('category_meta_title')->nullable();
            $table->string('category_meta_keyword')->nullable();
            $table->string('category_meta_desc')->nullable();
            $table->integer('category_order')->nullable();
            $table->boolean('category_status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
