<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Issue;
use App\Models\TeacherSubject;
use App\Models\Department;
use App\Models\IssueDepartment;
use Illuminate\Support\Facades\DB;

class IssueManagementController extends Controller
{
	private function validateRegisterIssue(Request $request): array
	{
		$validatedData = $request->validate([
			'teacher_subject_id' => 'required|string|exists:teacher_subjects,teacher_subject_id',
			'name' => 'required|string',
			'due_date' => 'required|date',
			'comment' => 'required|string',
			'task_number' => 'required|string',
			'private_flag' => 'required|boolean',
			'department_ids' => 'required|array',
			'challenge_flag' => 'sometimes|boolean',
			'challenge_max_score' => 'sometimes|integer',
		]);

		return $validatedData;
	}

	private function validateUpdateIssue(Request $request): array
	{
		$validatedData = $request->validate([
			'teacher_subject_id' => 'sometimes|string|exists:teacher_subjects,teacher_subject_id',
			'name' => 'sometimes|string',
			'due_date' => 'sometimes|date',
			'comment' => 'sometimes|string',
			'task_number' => 'sometimes|string',
			'private_flag' => 'sometimes|boolean',
			'challenge_flag' => 'sometimes|boolean',
			'challenge_max_score' => 'sometimes|integer',
		]);

		return $validatedData;
	}

	private function validateRegisterIssueDepartment(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_id' => 'required|string|exists:issues,issue_id',
			'department_id' => 'required|string|exists:departments,department_id',
		]);

		return $validatedData;
	}

	public function registerIssue(Request $request): JsonResponse
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validateRegisterIssue($request);

			$issue = Issue::registerNewIssue($validatedData);
			$issueId = $issue->issue_id;

			foreach ($validatedData['department_ids'] as $departmentId) {
				$issueDepartment = IssueDepartment::registerNewIssueDepartment([
					'issue_id' => $issueId,
					'department_id' => $departmentId,
				]);
			}

			DB::commit();
			return response()->json(
				[
					'issue' => $issue,
					'issue_department' => $issueDepartment,
				],
				201
			);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function getIssueList(): JsonResponse
	{
		try {
			$issues = Issue::all();
			return response()->json($issues);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function updateIssue(Request $request): JsonResponse
	{
		try {
			$validatedData = $this->validateUpdateIssue($request);

			$issue = Issue::updateIssue($validatedData);

			return response()->json($issue, 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function deleteIssue(string $issue_id, bool $isPhysicalDeletion = false): JsonResponse
	{
		try {
			$issue = Issue::find($issue_id);
			if ($isPhysicalDeletion) {
				$issue->delete(); // 物理削除
				return response()->json(
					['message' => 'Issue deleted successfully (physical deletion)'],
					201
				);
			} else {
				$issue->private_flag = true; // 論理削除
				$issue->save();
				return response()->json(
					['message' => 'Issue deleted successfully (logical deletion)'],
					201
				);
			}
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function registerIssueDepartment(Request $request): JsonResponse
	{
		try {
			$validatedData = $this->validateRegisterIssueDepartment($request);

			$issueDepartment = IssueDepartment::registerNewIssueDepartment($validatedData);

			return response()->json($issueDepartment, 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function deleteIssueDepartment(string $issue_department_id): JsonResponse
	{
		try {
			$issueDepartment = IssueDepartment::find($issue_department_id);
			$issueDepartment->delete();
			return response()->json(['message' => 'IssueDepartment deleted successfully'], 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}
}
