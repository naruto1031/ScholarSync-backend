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
		Schema::table('issues', function (Blueprint $table) {
			$table->string('task_number')->nullable();
			$table->boolean('private_flag')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('issues', function (Blueprint $table) {
			$table->dropColumn('task_number');
			$table->dropColumn('private_flag');
		});
	}
};
