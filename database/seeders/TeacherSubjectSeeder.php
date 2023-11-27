<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\TeacherSubject;

class TeacherSubjectSeeder extends Seeder
{
	public function run()
	{
		TeacherSubject::factory()
			->count(30)
			->create();
	}
}
