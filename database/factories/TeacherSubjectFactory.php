<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TeacherSubject;
use App\Models\Teacher;
use App\Models\Subject;

class TeacherSubjectFactory extends Factory
{
	protected $model = TeacherSubject::class;

	public function definition(): array
	{
		return [
			'teacher_id' => Teacher::factory(),
			'subject_id' => Subject::factory(),
		];
	}
}
