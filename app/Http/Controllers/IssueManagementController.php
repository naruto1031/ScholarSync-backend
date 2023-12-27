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
		]);

		return $validatedData;
	}

	private function validateUpdateIssue(Request $request): array
	{
		$validatedData = $request->validate([
			'teacher_subject_id' => 'required|string|exists:teacher_subjects,teacher_subject_id',
			'name' => 'required|string',
			'due_date' => 'required|date',
			'comment' => 'required|string',
			'task_number' => 'required|string',
			'private_flag' => 'required|boolean',
		]);

		return $validatedData;
	}

	public function registerIssue(Request $request): JsonResponse
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validateRegisterIssue($request);

			// 課題を登録
			$issue = new Issue([
				'teacher_subject_id' => $validatedData['teacher_subject_id'],
				'name' => $validatedData['name'],
				'due_date' => $validatedData['due_date'],
				'comment' => $validatedData['comment'],
				'task_number' => $validatedData['task_number'],
				'private_flag' => $validatedData['private_flag'],
			]);
			$issue->save();

			// 課題を実施する学科を設定
			$departmentIds = $validatedData['department_ids'];

			foreach ($departmentIds as $departmentId) {
				$issueDepartment = new IssueDepartment([
					'issue_id' => $issue->issue_id,
					'department_id' => $departmentId,
				]);
				$issueDepartment->save();
			}

			DB::commit();
			return response()->json(['message' => 'Issue registered successfully'], 201);
		} catch (\Exception $e) {
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

	public function updateIssue(Request $request, string $issue_id): JsonResponse
	{
		try {
			$validatedData = $this->validateUpdateIssue($request);

			$issue = Issue::find($issue_id);
			$issue->teacher_subject_id = $validatedData['teacher_subject_id'];
			$issue->name = $validatedData['name'];
			$issue->due_date = $validatedData['due_date'];
			$issue->comment = $validatedData['comment'];
			$issue->task_number = $validatedData['task_number'];
			$issue->private_flag = $validatedData['private_flag'];
			$issue->save();

			return response()->json(['message' => 'Issue updated successfully'], 201);
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
			$validatedData = $request->validate([
				'issue_id' => 'required|string|exists:issues,issue_id',
				'department_id' => 'required|string|exists:departments,department_id',
			]);

			$issueDepartment = new IssueDepartment([
				'issue_id' => $validatedData['issue_id'],
				'department_id' => $validatedData['department_id'],
			]);

			$issueDepartment->save();

			return response()->json(['message' => 'IssueDepartment registered successfully'], 201);
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
