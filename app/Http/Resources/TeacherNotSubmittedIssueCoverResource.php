<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherNotSubmittedIssueCoverResource extends JsonResource
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
			'issue_id' => 0,
			'comment' => '',
			'student_name' => $this->name,
			'registration_number' => $this->registration_number,
			'attendance_number' => $this->attendance_number,
			'issue_cover_status_id' => 0,
			'status' => 'not_submitted',
			'evaluation' => null,
			'current_score' => null,
			'resubmission_deadline' => null,
			'resubmission_comment' => null,
		];
	}
}
