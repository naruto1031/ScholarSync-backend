<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Department;

class DepartmentManagementController extends Controller
{
	private function validateDepartment(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string',
		]);

		return $validatedData;
	}

	public function departmentRegister(Request $request): JsonResponse
	{
		$validatedData = $this->validateDepartment($request);
		$department = new Department([
			'name' => $validatedData['name'],
		]);

		$department->save();

		return response()->json(['message' => 'Department registered successfully'], 201);
	}

	public function getDepartmentList(): JsonResponse
	{
		$departments = Department::all();
		return response()->json($departments);
	}

	public function updateDepartment(Request $request, string $department_id): JsonResponse
	{
		$validatedData = $this->validateDepartment($request);

		$department = Department::find($department_id);
		$department->name = $validatedData['name'];
		$department->save();

		return response()->json(['message' => 'Department updated successfully'], 201);
	}

	public function deleteDepartment(string $department_id): JsonResponse
	{
		$department = Department::find($department_id);
		$department->delete();

		return response()->json(['message' => 'Department deleted successfully'], 201);
	}
}
