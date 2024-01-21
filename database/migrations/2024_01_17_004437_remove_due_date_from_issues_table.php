<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('issues', function (Blueprint $table) {
			$table->dropColumn('due_date');
		});
	}

	public function down()
	{
		Schema::table('issues', function (Blueprint $table) {
			$table->dateTime('due_date')->nullable();
		});
	}
};
