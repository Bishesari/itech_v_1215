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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);                 // مرکزی بوشهر
            $table->string('code', 7)->unique();       // BUS-001

            $table->foreignId('province_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();

            $table->string('address', 150)->nullable();  // آدرس کامل
            $table->string('postal_code', 10)->nullable();

            $table->string('phone', 11)->nullable();
            $table->string('mobile', 11)->nullable();

            $table->decimal('remain_credit', 12, 0)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
