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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('title', 50);
            $table->string('slug')->unique();

            // منطق تجاری
            // پرداخت دوره ای است؟
            $table->boolean('is_subscription')->default(false);
            // قابل خرید چندباره است؟
            $table->boolean('is_repeatable')->default(false);
            // مدت زمان دارد؟ مثلا 30 روزه است؟
            $table->boolean('has_duration')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
