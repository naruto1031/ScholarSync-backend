<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IssueCoverStatus;

class IssueCoverStatusSeeder extends Seeder
{
	public function run()
	{
		IssueCoverStatus::factory()
			->count(50)
			->create();
	}
}
