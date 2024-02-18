<?php

namespace App\Http\Controllers;

use App\Http\Resources\IssueCoverClassResource;
use Illuminate\Http\Request;
use App\Models\IssueCover;
use App\Models\IssueCoverStatus;
use App\Models\SchoolClass;
use App\Models\Issue;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\IssueCoverResource;
use App\Http\Resources\NotSubmittedIssueCoverResource;
use App\Http\Resources\SearchConditionIssueCoverResource;
use App\Http\Resources\TeacherNotSubmittedIssueCoverResource;
use Illuminate\Support\Facades\Log;

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
			'attendance_numbers' => 'sometimes|array',
			'exclude_attendance_numbers' => 'sometimes|array',
		]);
		return $validatedData;
	}

	private function validatedCollectiveUpdateIssueCovers(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_cover_ids' => 'required|array',
			'issue_cover_ids.*' => 'required|string|exists:issue_covers,issue_cover_id',
			'status' => 'required|string',
			'evaluation' => 'sometimes|string',
			'resubmission_deadline' => 'sometimes|string',
			'resubmission_comment' => 'sometimes|string',
		]);
		return $validatedData;
	}

	private function validatedIndividualUpdateIssueCover(Request $request): array
	{
		$validatedData = $request->validate([
			'issue_cover_id' => 'required|string|exists:issue_covers,issue_cover_id',
			'status' => 'required|string',
			'evaluation' => 'sometimes|string',
			'current_score' => 'sometimes|int',
			'resubmission_deadline' => 'sometimes|string',
			'resubmission_comment' => 'sometimes|string',
		]);
		return $validatedData;
	}

	private function validatedFindIssueCoverByClassIdAndSubjectId(Request $request): array
	{
		$validatedData = $request->validate([
			'class_id' => 'required|string|exists:school_classes,class_id',
			'subject_id' => 'required|string|exists:subjects,subject_id',
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

			if ($validatedData['status'] === 'not_submitted') {
				$issueCovers = TeacherNotSubmittedIssueCoverResource::collection(
					IssueCover::findNotSubmittedByIssueIdAndClassId($validatedData)
				);
				return response()->json(['issue_covers' => $issueCovers], 200);
			}

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

	public function updateCollectiveIssueCovers(Request $request)
	{
		try {
			DB::beginTransaction();
			$validatedData = $this->validatedCollectiveUpdateIssueCovers($request);
			foreach ($validatedData['issue_cover_ids'] as $issueCoverId) {
				IssueCover::updateIssueCoverStatus(
					$issueCoverId,
					$validatedData['status'],
					$validatedData['evaluation'] ?? null,
					$validatedData['resubmission_deadline'] ?? null,
					$validatedData['resubmission_comment'] ?? null
				);
			}

			$issueCovers = SearchConditionIssueCoverResource::collection(
				IssueCover::findIssueCoverByIssueCoverId($validatedData['issue_cover_ids'])
			);
			DB::commit();
			return response()->json(['issue_covers' => $issueCovers], 200);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function updateIndividualIssueCover(Request $request)
	{
		try {
			DB::beginTransaction();
			$validatedData = $this->validatedIndividualUpdateIssueCover($request);
			IssueCover::updateIssueCover(
				$validatedData['issue_cover_id'],
				$validatedData['status'],
				$validatedData['evaluation'] ?? null,
				$validatedData['resubmission_deadline'] ?? null,
				$validatedData['resubmission_comment'] ?? null,
				$validatedData['current_score'] ?? null
			);
			$issueCover = SearchConditionIssueCoverResource::collection(
				IssueCover::findIssueCoverByIssueCoverId([$validatedData['issue_cover_id']])
			);
			DB::commit();
			return response()->json(['issue_covers' => $issueCover], 200);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}

	public function findIssueCoverByClassIdAndSubjectId(Request $request)
	{
		try {
			$validatedData = $this->validatedFindIssueCoverByClassIdAndSubjectId($request);
			$issues = Issue::findBySubjectId($validatedData['subject_id']);
			$students = SchoolClass::find($validatedData['class_id'])->students;
			$issueCovers = [];
			foreach ($students as $student) {
				$issueCovers[] = IssueCoverClassResource::collection(
					IssueCover::findByStudentIdAndSubjectId(
						$student->student_id,
						$validatedData['subject_id']
					)
				);
			}
			return response()->json(
				[
					'issues' => $issues,
					'students' => $students,
					'issue_covers' => $issueCovers,
				],
				200
			);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}
}
