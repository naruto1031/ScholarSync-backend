<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssueCover;
use App\Models\IssueCoverStatus;
use Illuminate\Support\Facades\DB;

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

	public function registerIssueCover(Request $request)
	{
		DB::beginTransaction();
		try {
			$validatedData = $this->validatedRegisterIssueCover($request);
			$validatedData['student_id'] = $request->attributes->get('jwt_sub');
			$issueCover = IssueCover::registerNewIssueCover([
				'issue_id' => $validatedData['issue_id'],
				'student_id' => $validatedData['student_id'],
				'comment' => $validatedData['comment'],
			]);

			$issueCoverStatus = IssueCoverStatus::registerNewIssueCoverStatus([
				'issue_cover_id' => $issueCover->issue_cover_id,
				'status' => $validatedData['status'],
			]);
			DB::commit();
			return response()->json(
				['issue_cover' => $issueCover, 'issue_cover_status' => $issueCoverStatus],
				201
			);
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
			$issueCovers = IssueCover::findByStatuses($validatedData['statuses']);
			return response()->json(['issue_covers' => $issueCovers], 200);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 400);
		}
	}
}
