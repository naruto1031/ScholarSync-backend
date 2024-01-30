<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssueCover;
use App\Models\IssueCoverStatus;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\IssueCoverResource;
use App\Http\Resources\NotSubmittedIssueCoverResource;
use App\Http\Resources\SearchConditionIssueCoverResource;

class IssueCoverManagementController extends Controller
{
	private function validatedRegisterIssueCover(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_id' => 'required|string|exists:issues,issue_id',
			'status' => 'required|string',
			'comment' => 'sometimes|string',
		]);
		return $validatedData;
	}

	private function validatedUpdateIssueCover(Request $request): array
	{
		$validatedData = $request->validate([
			'status' => 'required|string',
			'comment' => 'sometimes|string',
		]);
		return $validatedData;
	}

	private function validatedSearchIssueCover(Request $request): array
	{
		$validatedData = $request->validate([
			'statuses' => 'required|array',
		]);
		return $validatedData;
	}

	private function validatedSearchIssueCoverByIssueId(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_id' => 'required|string|exists:issues,issue_id',
			'class_id' => 'required|string|exists:school_classes,class_id',
			'status' => 'required|string',
			'student_numbers' => 'sometimes|array',
			'exclude_student_numbers' => 'sometimes|array',
		]);
		return $validatedData;
	}

	public function registerIssueCover(Request $request)
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validatedRegisterIssueCover($request);
			$validatedData['student_id'] = $request->attributes->get('jwt_sub');
			$issueCover = IssueCover::registerNewIssueCover([
				'issue_id' => $validatedData['issue_id'],
				'student_id' => $validatedData['student_id'],
				'comment' => $validatedData['comment'] ?? '',
			]);

			$issueCoverStatus = IssueCoverStatus::registerNewIssueCoverStatus([
				'issue_cover_id' => $issueCover->issue_cover_id,
				'status' => $validatedData['status'],
			]);

			$responseData = $this->getNotSubmittedIssueCover($request);
			DB::commit();

			return response()->json($responseData->original, 201);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function deleteIssueCover(Request $request)
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validatedUpdateIssueCover($request);
			$validatedData['student_id'] = $request->attributes->get('jwt_sub');
			IssueCover::deleteIssueCover($validatedData['issue_cover_id']);
			DB::commit();
			return response()->json(['message' => '提出を取り下げました'], 200);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function searchIssueCover(Request $request)
	{
		try {
			$validatedData = $this->validatedSearchIssueCover($request);
			$studentId = $request->attributes->get('jwt_sub');
			$issueCovers = IssueCoverResource::collection(
				IssueCover::findByStatusesAndStudentId($validatedData['statuses'], $studentId)
			);

			if (in_array('not_submitted', $validatedData['statuses'])) {
				$notSubmittedIssueCovers = NotSubmittedIssueCoverResource::collection(
					IssueCover::findNotSubmittedByStudentId($studentId)
				);
				$issueCovers = $issueCovers->merge($notSubmittedIssueCovers);
			}
			return response()->json(['issue_covers' => $issueCovers], 200);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function searchIssueCoverByIssueId(Request $request)
	{
		try {
			$validatedData = $this->validatedSearchIssueCoverByIssueId($request);

			$issueCovers = SearchConditionIssueCoverResource::collection(
				IssueCover::findBySearchCondition($validatedData)
			);
			return response()->json(['issue_covers' => $issueCovers], 200);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function getNotSubmittedIssueCover(Request $request)
	{
		try {
			$studentId = $request->attributes->get('jwt_sub');
			$issues = IssueCover::findNotSubmittedByStudentId($studentId);
			return response()->json(['issues' => $issues], 200);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}
}
