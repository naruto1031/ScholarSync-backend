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
			$table->id('student_id');
			$table->unsignedBigInteger('class_id');
			$table->string('email');
			$table->string('name');
			$table->string('registration_number');
			$table->integer('attendance_number');
			$table->timestamps();

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
		Schema::dropIfExists('students');
	}
};
