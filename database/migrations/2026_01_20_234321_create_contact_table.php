<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact', function (Blueprint $table) {
            $table->increments('contact_id'); // int(10) UNSIGNED AUTO_INCREMENT
            $table->string('contact_name', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('contact_country', 255)->nullable();
            $table->integer('contact_prefix')->default(0);
            $table->string('contact_mobile', 255)->nullable();
            $table->string('contact_city', 255)->nullable();
            $table->string('contact_zipcode', 255)->nullable();
            $table->string('contact_subject', 255)->nullable();
            $table->text('contact_message')->nullable();
            $table->string('contact_ip', 45)->nullable();
            $table->unsignedBigInteger('contact_order')->nullable();
            $table->enum('contact_status', ['0', '1'])
                ->default('0')
                ->index();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
