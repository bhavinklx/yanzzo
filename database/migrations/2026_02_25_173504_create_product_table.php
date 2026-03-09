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
        Schema::create('product', function (Blueprint $table) {
            // IDs and Relationship Keys
            $table->increments('product_id');
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('subcategory_id')->nullable()->index();
            $table->unsignedBigInteger('state_id')->nullable()->index();
            $table->unsignedBigInteger('city_id')->nullable()->index();
            $table->string('product_listing_id')->unique();
            $table->string('product_title')->nullable();
            $table->string('product_slug')->nullable();
            $table->date('product_date')->nullable();
            $table->text('product_short_desc')->nullable();
            $table->text('product_desc')->nullable();
            $table->text('product_specification')->nullable();
            $table->decimal('product_price', 10, 2);
            $table->string('product_brand')->nullable();
            $table->string('product_model')->nullable();
            $table->string('product_location')->nullable();
            $table->text('product_meta_title')->nullable();
            $table->text('product_meta_keyword')->nullable();
            $table->text('product_meta_desc')->nullable();
            $table->unsignedInteger('product_order')->default(0);
            $table->enum('product_status', ['0', '1'])
                ->default('0')
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
