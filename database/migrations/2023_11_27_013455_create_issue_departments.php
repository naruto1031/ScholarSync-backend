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
		Schema::create('issue_departments', function (Blueprint $table) {
			$table->id('issue_department_id');
			$table->unsignedBigInteger('issue_id');
			$table->unsignedBigInteger('department_id');
			$table->timestamps();

			$table
				->foreign('issue_id')
				->references('issue_id')
				->on('issues')
				->onDelete('cascade');

			$table
				->foreign('department_id')
				->references('department_id')
				->on('departments')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('issue_departments');
	}
};
