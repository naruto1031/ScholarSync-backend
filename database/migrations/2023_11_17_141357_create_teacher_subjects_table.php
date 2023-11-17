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
		Schema::create('teacher_subjects', function (Blueprint $table) {
			$table->id('teacher_subject_id'); // 担当ID
			$table->unsignedBigInteger('teacher_id'); // 教師ID
			$table->unsignedBigInteger('subject_id'); // 教科ID
			$table->unsignedBigInteger('department_id'); // 学科ID
			$table->timestamps();

			// 外部キー制約
			$table
				->foreign('teacher_id')
				->references('teacher_id')
				->on('teachers');
			$table
				->foreign('subject_id')
				->references('subject_id')
				->on('subjects');
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
		Schema::dropIfExists('teacher_subjects');
	}
};
