<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClassTeacher;
use App\Models\Teacher;
use App\Models\SchoolClass;

class ClassTeacherFactory extends Factory
{
	protected $model = ClassTeacher::class;

	public function definition(): array
	{
		return [
			'teacher_id' => Teacher::factory(),
			'class_id' => SchoolClass::factory(),
		];
	}
}
