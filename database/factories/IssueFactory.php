<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Issue;
use App\Models\TeacherSubject;

class IssueFactory extends Factory
{
	protected $model = Issue::class;

	public function definition(): array
	{
		return [
			'teacher_subject_id' => TeacherSubject::factory(),
			'name' => $this->faker->sentence,
			'due_date' => $this->faker->date,
			'comment' => $this->faker->paragraph,
		];
	}
}
