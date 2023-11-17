<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('students', function (Blueprint $table) {
			$table->id('student_id'); // 生徒ID
			$table->unsignedBigInteger('department_id'); // 学科ID
			$table->string('name'); // 生徒名
			$table->date('birth_date'); // 生年月日
			$table->string('registration_number'); // 学籍番号
			$table->string('grade'); // 学年
			$table->timestamps();

			// 外部キー制約
			$table
				->foreign('department_id')
				->references('department_id')
				->on('departments');
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
