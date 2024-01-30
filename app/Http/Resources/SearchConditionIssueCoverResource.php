<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchConditionIssueCoverResource extends JsonResource
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
			'comment' => $this->comment,
			'student_id' => $this->student_id,
			'student_name' => $this->student->name,
			'registration_number' => $this->student->registration_number,
			'attendance_number' => $this->student->attendance_number,
			'issue_cover_status_id' => $this->issueCoverStatus->issue_cover_status_id,
			'status' => $this->issueCoverStatus->status,
			'evaluation' => $this->issueCoverStatus->evaluation,
			'current_score' => $this->issueCoverStatus->current_score,
			'resubmission_deadline' => $this->issueCoverStatus->resubmission_deadline,
			'resubmission_comment' => $this->issueCoverStatus->resubmission_comment,
		];
	}
}
