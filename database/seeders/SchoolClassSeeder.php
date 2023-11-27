<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
	public function run()
	{
		SchoolClass::factory()
			->count(20)
			->create();
	}
}
