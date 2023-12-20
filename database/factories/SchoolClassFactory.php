<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SchoolClass;
use App\Models\Department;

class SchoolClassFactory extends Factory
{
	protected $model = SchoolClass::class;

	public function definition(): array
	{
		return [
			'department_id' => Department::factory(),
			'name' => $this->faker->word,
		];
	}
}
