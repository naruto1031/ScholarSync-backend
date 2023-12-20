<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IssueCover;

class IssueCoverSeeder extends Seeder
{
	public function run()
	{
		IssueCover::factory()
			->count(50)
			->create();
	}
}
