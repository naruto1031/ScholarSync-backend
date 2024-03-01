<?php

namespace App\Http\Controllers;

use App\Http\Resources\IssueResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Issue;
use App\Models\IssueClass;
use App\Models\IssueDepartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IssueManagementController extends Controller
{
	private function validateRegisterIssue(Request $request): array
	{
		$validatedData = $request->validate([
			'teacher_subject_id' => 'required|string|exists:teacher_subjects,teacher_subject_id',
			'name' => 'required|string',
			'due_dates' => 'required|array',
			'comment' => 'sometimes|string',
			'task_number' => 'required|string',
			'private_flag' => 'required|boolean',
			'challenge_flag' => 'sometimes|boolean',
			'challenge_max_score' => 'sometimes|integer',
		]);

		return $validatedData;
	}

	private function validateUpdateIssue(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_id' => 'required|string|exists:issues,issue_id',
			'teacher_subject_id' => 'required|string|exists:teacher_subjects,teacher_subject_id',
			'name' => 'sometimes|string',
			'due_dates' => 'sometimes|array',
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

			foreach ($validatedData['due_dates'] as $data) {
				IssueClass::registerNewIssueClass([
					'issue_id' => $issueId,
					'class_id' => $data['class_id'],
					'due_date' => $data['due_date'],
				]);
			}

			DB::commit();
			return response()->json($issue, 201);
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

	// teacher_subject_idを指定して、その教師が担当している課題を取得する
	public function getIssueListByTeacherSubjectId(string $teacher_subject_id): JsonResponse
	{
		try {
			$issues = IssueResource::collection(Issue::findIssueByTeacherSubjectId($teacher_subject_id));
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
			$teacher_subject_id = $validatedData['teacher_subject_id'];

			if (isset($validatedData['due_dates'])) {
				foreach ($validatedData['due_dates'] as $data) {
					if (isset($data['issue_class_id'])) {
						IssueClass::updateIssueClass([
							'issue_class_id' => $data['issue_class_id'],
							'issue_id' => $issue->issue_id,
							'class_id' => $data['class_id'],
							'due_date' => $data['due_date'],
						]);
					}
				}
			}

			$issues = IssueResource::collection(Issue::findIssueByTeacherSubjectId($teacher_subject_id));

			return response()->json($issues, 201);
		} catch (\Exception $e) {
			return response()->json(['error' => $e->getMessage()], 400);
		}
	}

	public function deleteIssue(string $issue_id, bool $isPhysicalDeletion = false): JsonResponse
	{
		try {
			$issue = Issue::find($issue_id);
			$issue->delete(); // 物理削除

			return response()->json(['message' => 'Issue deleted successfully (physical deletion)'], 201);
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
