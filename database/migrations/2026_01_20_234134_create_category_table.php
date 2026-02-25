<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id('category_id');
            $table->integer('category_parent')->default(0)->index()->comment('Category Parent Id');
            $table->string('category_title', 255)->nullable();
            $table->string('category_slug', 255)->nullable();
            $table->string('category_image', 255);
            $table->string('category_icon', 255)->nullable();
            $table->longText('category_short_desc')->nullable();
            $table->mediumText('category_desc')->nullable();
            $table->longText('category_meta_title')->nullable();
            $table->longText('category_meta_keyword')->nullable();
            $table->longText('category_meta_desc')->nullable();
            $table->integer('category_order')->nullable()->index();
            $table->enum('category_status', ['0', '1'])
                ->nullable()
                ->comment('0=Inactive, 1=Active')
                ->index();

            $table->enum('category_hstatus', ['0', '1'])
                ->nullable()
                ->comment('0=Inactive, 1=Active')
                ->index();

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};
