<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('school_classes', function (Blueprint $table) {
			$table->integer('student_count')->nullable();
		});
	}

	public function down()
	{
		Schema::table('school_classes', function (Blueprint $table) {
			$table->dropColumn('student_count');
		});
	}
};
