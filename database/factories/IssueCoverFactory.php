<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IssueCover;
use App\Models\Issue;
use App\Models\Student;

class IssueCoverFactory extends Factory
{
	protected $model = IssueCover::class;

	public function definition(): array
	{
		return [
			'issue_id' => Issue::factory(),
			'student_id' => Student::factory(),
			'comment' => $this->faker->paragraph,
		];
	}
}
