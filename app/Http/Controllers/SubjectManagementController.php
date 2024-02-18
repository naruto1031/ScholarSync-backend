<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;
use App\Models\SubjectDepartment;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectManagementController extends Controller
{
	private function validateSubject(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string',
			'department_ids' => 'required|array',
			'department_ids.*' => 'required|exists:departments,department_id',
		]);

		return $validatedData;
	}

	private function validateSubjectUpdate(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'sometimes|string',
			'department_ids' => 'sometimes|array',
			'department_ids.*' => 'required|exists:departments,department_id',
		]);

		return $validatedData;
	}

	public function subjectRegister(Request $request): JsonResponse
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validateSubject($request);

			$subject = Subject::registerNewSubject([
				'name' => $validatedData['name'],
			]);

			foreach ($validatedData['department_ids'] as $department_id) {
				SubjectDepartment::registerNewSubjectDepartment([
					'subject_id' => $subject->subject_id,
					'department_id' => $department_id,
				]);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['message' => $e->getMessage()], 400);
		}

		return response()->json($subject, 201);
	}

	public function updateSubject(Request $request, string $subject_id): JsonResponse
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validateSubjectUpdate($request);
			if (isset($validatedData['name'])) {
				$subject = Subject::updateSubject([
					'subject_id' => $subject_id,
					'name' => $validatedData['name'],
				]);
			}

			if (isset($validatedData['department_ids'])) {
				SubjectDepartment::where('subject_id', $subject_id)->delete();
				foreach ($validatedData['department_ids'] as $department_id) {
					SubjectDepartment::registerNewSubjectDepartment([
						'subject_id' => $subject_id,
						'department_id' => $department_id,
					]);
				}
			}
			DB::commit();
			return response()->json(['message' => 'Subject updated successfully'], 201);
		} catch (\Exception $e) {
			DB::rollback();
			return response()->json(['message' => 'Failed to update subject'], 400);
		}
	}

	public function deleteSubject(string $subject_id): JsonResponse
	{
		$subject = Subject::find($subject_id);
		$subject->delete();

		return response()->json(['message' => 'Subject deleted successfully'], 201);
	}

	public function getSubjectList(): JsonResponse
	{
		$subjects = Subject::all();
		$subjects = $subjects->map(function ($subject) {
			return [
				'id' => $subject->subject_id,
				'name' => $subject->name,
				'department_ids' => $subject->subjectDepartments->pluck('department_id')->toArray(),
			];
		});
		return response()->json($subjects);
	}

	public function getSubjectListByClassId(string $classId): JsonResponse
	{
		$subjects = Subject::findSubjectListByClassId($classId);
		return response()->json($subjects);
	}
}
