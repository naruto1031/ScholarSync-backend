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
		// issues テーブルにカラムを追加
		Schema::table('issues', function (Blueprint $table) {
			$table->boolean('challenge_flag')->default(false);
			$table->integer('challenge_max_score')->nullable();
		});

		// issue_cover_statuses テーブルからカラムを削除
		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			$table->dropColumn(['challenge_flag', 'challenge_max_score']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		// issues テーブルからカラムを削除
		Schema::table('issues', function (Blueprint $table) {
			$table->dropColumn(['challenge_flag', 'challenge_max_score']);
		});

		// issue_cover_statuses テーブルにカラムを追加
		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			$table->boolean('challenge_flag')->default(false);
			$table->integer('challenge_max_score')->nullable();
		});
	}
};
