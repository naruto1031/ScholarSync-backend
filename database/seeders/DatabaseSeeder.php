<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call([
			DepartmentSeeder::class,
			SchoolClassSeeder::class,
			StudentSeeder::class,
			TeacherSeeder::class,
			ClassTeacherSeeder::class,
			SubjectSeeder::class,
			TeacherSubjectSeeder::class,
			IssueSeeder::class,
			IssueDepartmentSeeder::class,
			IssueCoverSeeder::class,
			IssueCoverStatusSeeder::class,
		]);
	}
}
