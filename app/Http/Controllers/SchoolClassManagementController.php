<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\SchoolClass;
use App\Models\Department;

class SchoolClassManagementController extends Controller
{
	private function validateClass(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string|unique:school_classes,name',
			'department_id' => 'required|string|exists:departments,department_id',
			'student_count' => 'required|integer',
		]);

		return $validatedData;
	}

	private function validateUpdateClass(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string|unique:school_classes,name',
			'student_count' => 'required|integer',
		]);

		return $validatedData;
	}

	public function classRegister(Request $request): JsonResponse
	{
		try {
			$validatedData = $this->validateClass($request);

			$class = new SchoolClass([
				'name' => $validatedData['name'],
				'department_id' => $validatedData['department_id'],
			]);

			$class->save();

			return response()->json(['message' => 'Class registered successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function getClassList(): JsonResponse
	{
		try {
			$classes = SchoolClass::withDepartment()->get();

			$formattedClasses = $classes->map(function ($class) {
				return [
					'class_id' => $class->class_id,
					'name' => $class->department->name . $class->name,
				];
			});
			return response()->json($formattedClasses);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function updateClass(Request $request, string $class_id): JsonResponse
	{
		try {
			$validatedData = $this->validateUpdateClass($request);

			$class = SchoolClass::find($class_id);
			$class->name = $validatedData['name'];
			$class->save();

			return response()->json(['message' => 'Class updated successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function deleteClass(string $class_id): JsonResponse
	{
		try {
			$class = SchoolClass::find($class_id);
			$class->delete();

			return response()->json(['message' => 'Class deleted successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}
}
