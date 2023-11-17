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
		Schema::create('teachers', function (Blueprint $table) {
			$table->id('teacher_id'); // 教師ID
			$table->string('name'); // 教師名
			$table->text('additional_info')->nullable(); // その他情報
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('teachers');
	}
};
