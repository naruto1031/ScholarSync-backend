<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssueClassesTable extends Migration
{
	public function up()
	{
		Schema::create('issue_classes', function (Blueprint $table) {
			$table->id('issue_class_id');
			$table->unsignedBigInteger('issue_id');
			$table->unsignedBigInteger('class_id');
			$table->dateTime('due_date');
			$table->timestamps();

			$table
				->foreign('issue_id')
				->references('issue_id')
				->on('issues')
				->onDelete('cascade');

			$table
				->foreign('class_id')
				->references('class_id')
				->on('school_classes')
				->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('issue_classes');
	}
}
