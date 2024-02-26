<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAuditableIdToStringInAuditsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('audits', function (Blueprint $table) {
			// auditable_id列をstringに変更
			$table->string('auditable_id')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('audits', function (Blueprint $table) {
			// ここでは元のunsignedBigIntegerに戻す処理を記述
			$table->unsignedBigInteger('auditable_id')->change();
		});
	}
}
