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
        Schema::create('issues', function (Blueprint $table) {
            $table->id('issue_id'); // 課題ID
            $table->foreignId('teacher_subject_id')->constrained('teacher_subjects'); // 担当ID
            $table->string('title'); // 課題名
            $table->date('due_date'); // 納期
            $table->text('comment')->nullable(); // コメント
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
