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
			$table->id('issue_id');
			$table->unsignedBigInteger('teacher_subject_id');
			$table->string('name');
			$table->date('due_date');
			$table->text('comment')->nullable();
			$table->timestamps();

			$table
				->foreign('teacher_subject_id')
				->references('teacher_subject_id')
				->on('teacher_subjects')
				->onDelete('cascade');
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
