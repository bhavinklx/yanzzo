<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('state', function (Blueprint $table) {
            $table->increments('state_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('state_name', 150);
            $table->enum('state_status', ['0', '1'])
                ->default('0')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('state');
    }
};