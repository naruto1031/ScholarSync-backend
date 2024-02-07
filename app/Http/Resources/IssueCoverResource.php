<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueCoverResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'issue_cover_id' => $this->issue_cover_id,
			'issue_id' => $this->issue_id,
			'subject' => $this->issue->teacherSubject->subject->name,
			'task_number' => $this->issue->task_number,
			'name' => $this->issue->name,
			'due_date' => $this->due_date,
			'comment' => $this->comment,
			'status' => $this->issueCoverStatus->status,
			'evaluation' => $this->issueCoverStatus->evaluation,
			'challenge_flag' => $this->issue->challenge_flag,
			'challenge_max_score' => $this->issue->challenge_max_score,
			'current_score' => $this->issueCoverStatus->current_score,
			'teacher_name' => $this->issue->teacherSubject->teacher->name,
			'resubmission_deadline' => $this->issueCoverStatus->resubmission_deadline,
			'resubmission_comment' => $this->issueCoverStatus->resubmission_comment,
		];
	}
}
