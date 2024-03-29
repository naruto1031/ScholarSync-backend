<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\TeacherSubject;
use App\Models\Subject;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectAssignmentController extends Controller
{
	private function validateSubjectAssignment(Request $request): array
	{
		$validatedData = $request->validate([
			'subject_id' => 'required|array',
			'subject_id.*' => 'required|string',
		]);

		return $validatedData;
	}

	public function assignSubjectToTeacher(Request $request): JsonResponse
	{
		$validatedData = $this->validateSubjectAssignment($request);
		$teacherId = $request->attributes->get('jwt_sub');

		if (is_null($teacherId)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}
		DB::beginTransaction();
		try {
			foreach ($validatedData['subject_id'] as $subjectId) {
				$teacherSubject = new TeacherSubject([
					'teacher_id' => $teacherId,
					'subject_id' => $subjectId,
					'academic_year' => 2024,
				]);
				$teacherSubject->save();
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['message' => $e->getMessage()], 400);
		}
		return response()->json($teacherSubject, 201);
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
				'subject_id' => $teacherSubject->subject_id,
				'name' => $teacherSubject->subject->name,
				'departments' => $teacherSubject->subject->subjectDepartments
					->pluck('department_id')
					->map(function ($department_id) {
						return [
							'department_id' => $department_id,
							'name' => Department::find($department_id)->name,
							'classes' => Department::find($department_id)->schoolClasses->map(function ($class) {
								return [
									'class_id' => $class->class_id,
									'name' => $class->name,
								];
							}),
						];
					}),
			];
		});
		return response()->json($formattedSubjects);
	}
}
