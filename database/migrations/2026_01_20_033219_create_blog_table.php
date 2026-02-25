<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog', function (Blueprint $table) {
            $table->increments('blog_id'); // int(10) unsigned AUTO_INCREMENT
            $table->unsignedBigInteger('bcategory_id')->nullable()->index();
            $table->string('blog_title', 255)->nullable();
            $table->string('blog_slug', 255)->nullable();
            $table->date('blog_date')->nullable();
            $table->string('blog_image', 255)->nullable();
            $table->text('blog_short_desc')->nullable();
            $table->text('blog_desc')->nullable();
            $table->text('blog_meta_title')->nullable();
            $table->text('blog_meta_keyword')->nullable();
            $table->text('blog_meta_desc')->nullable();
            $table->string('blog_canonical', 255)->nullable();
            $table->unsignedInteger('blog_order')->default(0);
            $table->enum('blog_status', ['0', '1'])->default('0')->index();
            $table->enum('blog_popular_status', ['0', '1'])->nullable()->default('0')->index();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};

