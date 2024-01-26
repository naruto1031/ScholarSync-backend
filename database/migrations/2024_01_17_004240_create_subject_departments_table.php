<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectDepartmentsTable extends Migration
{
	public function up()
	{
		Schema::create('subject_departments', function (Blueprint $table) {
			$table->id('subject_department_id');
			$table->unsignedBigInteger('subject_id');
			$table->unsignedBigInteger('department_id');
			$table->timestamps();

			$table
				->foreign('subject_id')
				->references('subject_id')
				->on('subjects')
				->onDelete('cascade');

			$table
				->foreign('department_id')
				->references('department_id')
				->on('departments')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('subject_departments');
	}
}
