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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id'); // 生徒ID
            $table->foreignId('department_id')->constrained('departments'); // 学科ID
            $table->string('name'); // 生徒名
            $table->date('birth_date'); // 生年月日
            $table->string('registration_number'); // 学籍番号
            $table->string('grade'); // 学年
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
