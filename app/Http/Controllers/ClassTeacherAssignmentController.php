<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ClassTeacher;
use App\Models\SchoolClass;
use App\Models\Teacher;

class ClassTeacherAssignmentController extends Controller
{
	public function validateClassTeacherAssignment(Request $request): array
	{
		$validatedData = $request->validate([
			'class_id' => 'required|string|exists:school_classes,class_id',
		]);

		return $validatedData;
	}

	public function assignClassToTeacher(Request $request): JsonResponse
	{
		try {
			$validatedData = $this->validateClassTeacherAssignment($request);
			$teacherId = $request->attributes->get('jwt_sub');

			$classTeacherAssignment = new ClassTeacher([
				'class_id' => $validatedData['class_id'],
				'teacher_id' => $teacherId,
			]);

			$classTeacherAssignment->save();

			return response()->json(['message' => 'Class assigned to teacher successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function deleteClassTeacherAssignment(string $class_teacher_id): JsonResponse
	{
		try {
			$classTeacherAssignment = ClassTeacher::find($class_teacher_id);
			$classTeacherAssignment->delete();

			return response()->json(['message' => 'Class assignment deleted successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function getClassTeacherList(Request $request): JsonResponse
	{
		try {
			$teacherId = $request->attributes->get('jwt_sub');
			$classTeacherAssignments = ClassTeacher::withClassAndDepartment($teacherId)->get();
			$formattedClassTeacherAssignments = $classTeacherAssignments->map(function (
				$classTeacherAssignment
			) {
				$departmentName = $classTeacherAssignment->schoolClass->department->name;
				$className = $classTeacherAssignment->schoolClass->name;
				$departmentNameAndClassName = $departmentName . $className;
				return [
					'class_teacher_id' => $classTeacherAssignment->class_teacher_id,
					'class_name' => $departmentNameAndClassName,
				];
			});
			return response()->json($formattedClassTeacherAssignments);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}
}
