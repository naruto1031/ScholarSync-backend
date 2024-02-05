<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'issue_id' => $this->issue_id,
			'teacher_subject_id' => $this->teacher_subject_id,
			'name' => $this->name,
			'comment' => $this->comment,
			'task_number' => $this->task_number,
			'private_flag' => $this->private_flag,
			'challenge_flag' => $this->challenge_flag,
			'challenge_max_score' => $this->challenge_max_score,
			'issue_classes' => $this->issueClasses->map(function ($issueClass) {
				return [
					'issue_class_id' => $issueClass->issue_class_id,
					'issue_id' => $issueClass->issue_id,
					'class_id' => $issueClass->class_id,
					'due_date' => $issueClass->due_date,
					'department_name' => $issueClass->schoolClass->department->name,
					'class_name' => $issueClass->schoolClass->name,
					'student_count' => $issueClass->schoolClass->student_count,
				];
			}),
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}
}
