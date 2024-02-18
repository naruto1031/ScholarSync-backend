<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('student_class_histories', function (Blueprint $table) {
			$table->id('history_id');
			$table->string('student_id');
			$table->unsignedBigInteger('class_id');
			$table->year('academic_year');
			$table->timestamps();

			$table
				->foreign('student_id')
				->references('student_id')
				->on('students');
			$table
				->foreign('class_id')
				->references('class_id')
				->on('school_classes');
		});
	}

	public function down()
	{
		Schema::dropIfExists('student_class_histories');
	}
};
