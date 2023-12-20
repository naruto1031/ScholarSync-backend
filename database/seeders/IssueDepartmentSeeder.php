<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\IssueDepartment;

class IssueDepartmentSeeder extends Seeder
{
	public function run()
	{
		IssueDepartment::factory()
			->count(40)
			->create();
	}
}
