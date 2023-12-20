<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\TeacherSubject;

class SubjectAssignmentController extends Controller
{
	private function validateSubjectAssignment(Request $request): array
	{
		$validatedData = $request->validate([
			'subject_id' => 'required|string',
		]);

		return $validatedData;
	}

	public function assignSubjectToTeacher(Request $request): JsonResponse
	{
		$validatedData = $this->validateSubjectAssignment($request);
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$teacherSubject = new TeacherSubject([
			'teacher_id' => $teacher_id,
			'subject_id' => $validatedData['subject_id'],
		]);

		$teacherSubject->save();

		return response()->json(['message' => 'Subject assigned successfully'], 201);
	}

	public function deleteSubjectAssignment(string $teacher_subject_id): JsonResponse
	{
		$teacherSubject = TeacherSubject::find($teacher_subject_id);
		$teacherSubject->delete();

		return response()->json(['message' => 'Subject assignment deleted successfully'], 201);
	}

	public function getSubjectList(Request $request): JsonResponse
	{
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$teacherSubjects = TeacherSubject::withSubject($teacher_id)->get();

		$formattedSubjects = $teacherSubjects->map(function ($teacherSubject) {
			return [
				'teacher_subject_id' => $teacherSubject->teacher_subject_id,
				'name' => $teacherSubject->subject->name,
			];
		});

		return response()->json($formattedSubjects);
	}
}
