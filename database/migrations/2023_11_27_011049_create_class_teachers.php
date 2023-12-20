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
		Schema::create('class_teachers', function (Blueprint $table) {
			$table->id('class_teacher_id');
			$table->unsignedBigInteger('teacher_id');
			$table->unsignedBigInteger('class_id');
			$table->timestamps();

			$table
				->foreign('teacher_id')
				->references('teacher_id')
				->on('teachers')
				->onDelete('cascade');

			$table
				->foreign('class_id')
				->references('class_id')
				->on('school_classes')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('class_teachers');
	}
};
