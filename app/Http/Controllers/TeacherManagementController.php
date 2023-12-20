<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherManagementController extends Controller
{
	private function validateTeacher(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string',
			'email' => 'required|email',
		]);

		return $validatedData;
	}

	public function teacherRegister(Request $request): JsonResponse
	{
		$validatedData = $this->validateTeacher($request);
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$teacher = new Teacher([
			'teacher_id' => $teacher_id,
			'name' => $validatedData['name'],
			'email' => $validatedData['email'],
		]);
		$teacher->save();

		return response()->json(['message' => 'Teacher registered successfully'], 201);
	}

	public function checkTeacherExists(Request $request): JsonResponse
	{
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$exists = Teacher::where('teacher_id', $teacher_id)->exists();

		return response()->json(['exists' => $exists]);
	}

	public function getTeacherList(): JsonResponse
	{
		$teachers = Teacher::all();
		return response()->json($teachers);
	}

	public function updateTeacher(Request $request): JsonResponse
	{
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$teacher = Teacher::find($teacher_id);

		if (!$teacher) {
			return response()->json(['message' => 'Teacher not found'], 404);
		}

		$validatedData = $this->validateTeacher($request);
		$teacher->fill($validatedData)->save();

		return response()->json(['message' => 'Teacher updated successfully']);
	}

	public function deleteTeacher(Request $request): JsonResponse
	{
		$teacher_id = $request->attributes->get('jwt_sub');

		if (is_null($teacher_id)) {
			return response()->json(['message' => 'JWT subject cannot be null'], 400);
		}

		$teacher = Teacher::find($teacher_id);

		if (!$teacher) {
			return response()->json(['message' => 'Teacher not found'], 404);
		}

		$teacher->delete();

		return response()->json(['message' => 'Teacher deleted successfully']);
	}
}
