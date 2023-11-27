<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

class TeacherFactory extends Factory
{
	protected $model = Teacher::class;

	public function definition(): array
	{
		return [
			'name' => $this->faker->name,
			'email' => $this->faker->unique()->safeEmail,
		];
	}
}
