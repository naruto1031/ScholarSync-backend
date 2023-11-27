<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Issue;

class IssueSeeder extends Seeder
{
	public function run()
	{
		Issue::factory()
			->count(40)
			->create();
	}
}
