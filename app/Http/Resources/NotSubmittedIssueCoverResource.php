<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotSubmittedIssueCoverResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'issue_cover_id' => 0,
			'issue_id' => $this->issue_id,
			'subject' => $this->subject_name,
			'task_number' => $this->task_number,
			'name' => $this->name,
			'due_date' => $this->due_date,
			'comment' => $this->comment,
			'status' => 'not_submitted',
			'evaluation' => null,
			'challenge_flag' => 0,
			'challenge_max_score' => null,
			'current_score' => null,
			'teacher_name' => '',
			'resubmission_deadline' => null,
			'resubmission_comment' => null,
		];
	}
}
