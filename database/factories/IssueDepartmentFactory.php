<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IssueDepartment;
use App\Models\Issue;
use App\Models\Department;

class IssueDepartmentFactory extends Factory
{
	protected $model = IssueDepartment::class;

	public function definition(): array
	{
		return [
			'issue_id' => Issue::factory(),
			'department_id' => Department::factory(),
		];
	}
}
