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
		Schema::create('issues', function (Blueprint $table) {
			$table->id('issue_id'); // 課題ID
			$table->unsignedBigInteger('teacher_subject_id'); // 担当ID
			$table->string('title'); // 課題名
			$table->date('due_date'); // 納期
			$table->text('comment')->nullable(); // コメント
			$table->timestamps();

			// 外部キー制約
			$table
				->foreign('teacher_subject_id')
				->references('teacher_subject_id')
				->on('teacher_subjects');
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
