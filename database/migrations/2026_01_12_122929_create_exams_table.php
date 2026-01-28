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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['quiz', 'midterm', 'final']);
            $table->foreignId('standard_id')->constrained()->cascadeOnDelete();
            $table->string('title', 70);
            $table->tinyInteger('que_qty')->default(40)->unsigned();
            $table->text('que_ids')->nullable(); // ترتیب سؤالات برای هر کاربر (اختیاری اما مفید)
            $table->dateTime('start')->nullable();
            $table->tinyInteger('duration')->unsigned();
            $table->dateTime('end')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
