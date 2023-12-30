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
		Schema::table('students', function (Blueprint $table) {
			// attendance_numberの型をstringに変更
			$table->string('attendance_number')->change();

			// registration_numberにユニーク制約を追加
			$table->unique('registration_number');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('students', function (Blueprint $table) {
			// attendance_numberの型をintegerに戻す
			$table->integer('attendance_number')->change();

			// registration_numberのユニーク制約を削除
			$table->dropUnique('students_registration_number_unique');
		});
	}
};
