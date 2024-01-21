<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDueDateOnIssueClassesTable extends Migration
{
	public function up()
	{
		Schema::table('issue_classes', function (Blueprint $table) {
			$table
				->dateTime('due_date')
				->nullable()
				->default(null)
				->change();
		});
	}

	public function down()
	{
		Schema::table('issue_classes', function (Blueprint $table) {
			$table->dateTime('due_date')->change();
		});
	}
}
