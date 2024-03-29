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
		Schema::create('issue_cover_statuses', function (Blueprint $table) {
			$table->id('issue_cover_status_id'); // 課題表紙状態ID
			$table->unsignedBigInteger('issue_cover_id'); // 課題表紙ID
			$table->boolean('approval_flag'); // 承認フラグ
			$table->boolean('unsubmitted_flag'); // 未提出フラグ
			$table->boolean('absence_flag'); // 公欠フラグ
			$table->boolean('exemption_flag'); // 免除フラグ
			$table->boolean('resubmission_flag'); // 再提出フラグ
			$table->date('resubmission_deadline')->nullable(); // 再提出期限
			$table->text('resubmission_comment')->nullable(); // 再提出コメント
			$table->boolean('challenge_flag'); // チャレンジ問題フラグ
			$table->integer('challenge_max_score')->nullable(); // チャレンジ問題の満点
			$table->integer('current_score')->nullable(); // 現在の点数
			$table->boolean('public_flag'); // 公開フラグ
			$table->timestamps();

			// 外部キー制約
			$table
				->foreign('issue_cover_id')
				->references('issue_cover_id')
				->on('issue_covers')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('issue_cover_statuses');
	}
};
