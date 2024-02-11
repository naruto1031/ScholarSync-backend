<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherInfoResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'name' => $this->name,
			'email' => $this->email,
			'teacher_subjects' => $this->teacherSubjects->map(function ($teacherSubject) {
				return [
					'subject_id' => $teacherSubject->subject_id,
					'subject_name' => $teacherSubject->subject->name,
				];
			}),
			'class_teacher' => $this->classTeachers->map(function ($classTeacher) {
				return [
					'class_id' => $classTeacher->class_id,
					'name' => $classTeacher->schoolClass->department->name . $classTeacher->schoolClass->name,
				];
			}),
		];
	}
}
