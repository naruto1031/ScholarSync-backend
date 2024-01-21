<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::table('issues', function (Blueprint $table) {
			$table->dateTime('due_date')->change();
		});

		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			$table
				->dateTime('resubmission_deadline')
				->nullable()
				->change();
		});
	}

	public function down()
	{
		Schema::table('issues', function (Blueprint $table) {
			$table->date('due_date')->change();
		});

		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			$table
				->date('resubmission_deadline')
				->nullable()
				->change();
		});
	}
};
