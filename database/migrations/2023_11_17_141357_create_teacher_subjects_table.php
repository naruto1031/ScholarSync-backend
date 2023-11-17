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
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id('teacher_subject_id'); // 担当ID
            $table->foreignId('teacher_id')->constrained('teachers'); // 教師ID
            $table->foreignId('subject_id')->constrained('subjects'); // 教科ID
            $table->foreignId('department_id')->constrained('departments'); // 学科ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};
