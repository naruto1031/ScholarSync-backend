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
		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			// 既存のブーリアンフラグを削除
			$table->dropColumn([
				'approval_flag',
				'unsubmitted_flag',
				'absence_flag',
				'exemption_flag',
				'resubmission_flag',
				'public_flag',
			]);

			// 新たな列挙型カラムを追加
			$table->string('status')->default('pending');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('issue_cover_statuses', function (Blueprint $table) {
			// 既存の列挙型カラムを削除
			$table->dropColumn(['status']);

			// 新たなブーリアンフラグを追加
			$table->boolean('approval_flag'); // 承認フラグ
			$table->boolean('unsubmitted_flag'); // 未提出フラグ
			$table->boolean('absence_flag'); // 公欠フラグ
			$table->boolean('exemption_flag'); // 免除フラグ
			$table->boolean('resubmission_flag'); // 再提出フラグ
			$table->boolean('public_flag'); // 公開フラグ
		});
	}
};
