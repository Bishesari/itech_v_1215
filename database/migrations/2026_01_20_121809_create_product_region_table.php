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
        Schema::create('product_region', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // اگر محتوا برای کل استان باشد
            $table->foreignId('province_id')->nullable()->constrained()->cascadeOnDelete();

            // اگر محتوا برای شهر خاص باشد
            $table->foreignId('city_id')->nullable()->constrained()->cascadeOnDelete();

            $table->timestamps();

            // جلوگیری از ایجاد دو ردیف مشابه
            $table->unique(['product_id', 'province_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_regions');
    }
};
