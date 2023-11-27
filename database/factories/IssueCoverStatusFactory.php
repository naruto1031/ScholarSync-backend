<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IssueCoverStatus;

class IssueCoverStatusFactory extends Factory
{
	protected $model = IssueCoverStatus::class;

	public function definition(): array
	{
		return [
			'issue_cover_id' => \App\Models\IssueCover::factory(),
			'approval_flag' => $this->faker->boolean,
			'unsubmitted_flag' => $this->faker->boolean,
			'absence_flag' => $this->faker->boolean,
			'exemption_flag' => $this->faker->boolean,
			'resubmission_flag' => $this->faker->boolean,
			'resubmission_deadline' => $this->faker->boolean ? $this->faker->date() : null,
			'resubmission_comment' => $this->faker->boolean ? $this->faker->sentence : null,
			'challenge_flag' => $this->faker->boolean,
			'challenge_max_score' => $this->faker->boolean ? $this->faker->randomDigitNotNull : null,
			'current_score' => $this->faker->boolean ? $this->faker->randomDigit : null,
			'public_flag' => $this->faker->boolean,
		];
	}
}
