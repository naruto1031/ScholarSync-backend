<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetSeeder extends Seeder
{
	public function run()
	{
		// データベーステーブルを空にする
		DB::table('scholar_sync')->truncate();

		// 自動インクリメントの値をリセット
		DB::statement('ALTER TABLE scholar_sync AUTO_INCREMENT = 1');
	}
}
