<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('teacher_subjects', function (Blueprint $table) {
			$table->year('academic_year')->after('teacher_id');
		});
	}

	public function down()
	{
		Schema::table('teacher_subjects', function (Blueprint $table) {
			$table->dropColumn('academic_year');
		});
	}
};
