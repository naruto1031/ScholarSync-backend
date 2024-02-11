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
		Schema::create('discord_guilds', function (Blueprint $table) {
			$table->string('guild_id')->primary();
			$table->unsignedBigInteger('class_id');

			$table
				->foreign('class_id')
				->references('class_id')
				->on('school_classes')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('discord_guilds');
	}
};
