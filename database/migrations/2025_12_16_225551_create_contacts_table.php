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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('value', 100); // شماره موبایل یا شماره تلفن یا آدرس پست الکترونیک
            $table->enum('type', ['mobile', 'email', 'phone'])->default('mobile');

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['type', 'value']);

        });
    }
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
