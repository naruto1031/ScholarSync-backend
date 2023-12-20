<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassTeacher;

class ClassTeacherSeeder extends Seeder
{
	public function run(): void
	{
		ClassTeacher::factory()
			->count(20)
			->create();
	}
}
