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
        Schema::create('issue_covers', function (Blueprint $table) {
            $table->id('issue_cover_id'); // 課題表紙ID
            $table->foreignId('issue_id')->constrained('issues'); // 課題ID
            $table->foreignId('student_id')->constrained('students'); // 生徒ID
            $table->text('comment')->nullable(); // コメント
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_covers');
    }
};
