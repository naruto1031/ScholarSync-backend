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
		Schema::create('issue_covers', function (Blueprint $table) {
			$table->id('issue_cover_id'); // 課題表紙ID
			$table->unsignedBigInteger('issue_id'); // 課題ID
			$table->unsignedBigInteger('student_id'); // 生徒ID
			$table->text('comment')->nullable(); // コメント
			$table->timestamps();

			// 外部キー制約
			$table
				->foreign('issue_id')
				->references('issue_id')
				->on('issues')
				->onDelete('cascade');
			$table
				->foreign('student_id')
				->references('student_id')
				->on('students')
				->onDelete('cascade');
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
