<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Student;
use App\Models\SchoolClass;

class StudentManagementController extends Controller
{
	private function validatedRegisterStudent(Request $request): array
	{
		$validatedData = $request->validate([
			'class_id' => 'required|string|exists:school_classes,class_id',
			'email' => 'required|string',
			'name' => 'required|string',
			'registration_number' => 'required|string|unique:students,registration_number',
			'attendance_number' => 'required|string',
		]);
		return $validatedData;
	}

	private function validatedUpdateStudent(Request $request): array
	{
		$validatedData = $request->validate([
			'class_id' => 'required|string|exists:school_classes,class_id',
			'registration_number' => 'required|string',
			'attendance_number' => 'required|string',
		]);
		return $validatedData;
	}

	public function checkStudentExists(Request $request): JsonResponse
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			if (is_null($studentId)) {
				return response()->json(['message' => 'JWT subject cannot be null'], 400);
			}

			$exists = Student::where('student_id', $studentId)->exists();

			return response()->json(['exists' => $exists]);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function registerStudent(Request $request)
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			if (is_null($studentId)) {
				return response()->json(['message' => 'JWT subject cannot be null'], 400);
			}
			$validatedData = $this->validatedRegisterStudent($request);
			$validatedData['student_id'] = $studentId;
			$student = Student::registerNewStudent($validatedData);

			return response()->json($student, 201);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function getStudentInfo(Request $request): JsonResponse
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			if (is_null($studentId)) {
				return response()->json(['message' => 'JWT subject cannot be null'], 400);
			}

			$student = Student::find($studentId);
			$studentInfo = [
				'class_name' => $student->getClassDisplayName(),
				'registration_number' => $student->registration_number,
				'attendance_number' => $student->attendance_number,
			];

			return response()->json($studentInfo);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function updateStudent(Request $request): JsonResponse
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			if (is_null($studentId)) {
				return response()->json(['message' => 'JWT subject cannot be null'], 400);
			}

			$validatedData = $this->validatedUpdateStudent($request);
			$validatedData['student_id'] = $studentId;
			$student = Student::updateStudent($validatedData);

			return response()->json($student, 201);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function deleteStudent(Request $request): JsonResponse
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			if (is_null($studentId)) {
				return response()->json(['message' => 'JWT subject cannot be null'], 400);
			}
			Student::deleteStudent($studentId);
			return response()->json(['message' => 'Student deleted successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}
}
