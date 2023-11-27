<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;
use App\Models\SchoolClass;

class StudentFactory extends Factory
{
	protected $model = Student::class;

	public function definition(): array
	{
		return [
			'class_id' => SchoolClass::factory(),
			'email' => $this->faker->safeEmail,
			'name' => $this->faker->name,
			'registration_number' => $this->faker->unique()->numberBetween(1000, 9999),
			'attendance_number' => $this->faker->numberBetween(1, 30),
		];
	}
}
