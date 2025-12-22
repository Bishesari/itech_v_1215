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
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();

            $table->string('page_key', 100)->index();

            $table->string('fingerprint', 255)->index();

            // کاربر مهمان هم مجاز است
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->ipAddress('ip')->nullable();

            $table->text('user_agent')->nullable();

            $table->boolean('is_bot')->default(false);

            $table->timestamps();

            // جلوگیری از ثبت بازدید تکراری در یک بازه کوتاه (اختیاری)
            $table->unique(['page_key', 'fingerprint', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
