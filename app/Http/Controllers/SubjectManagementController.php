<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Subject;

class SubjectManagementController extends Controller
{
	private function validateSubject(Request $request): array
	{
		$validatedData = $request->validate([
			'name' => 'required|string',
		]);

		return $validatedData;
	}

	public function subjectRegister(Request $request): JsonResponse
	{
		$validatedData = $this->validateSubject($request);

		$subject = new Subject([
			'name' => $validatedData['name'],
		]);
		$subject->save();

		return response()->json(['message' => 'Subject registered successfully'], 201);
	}

	public function updateSubject(Request $request, string $subject_id): JsonResponse
	{
		$validatedData = $this->validateSubject($request);

		$subject = Subject::find($subject_id);
		$subject->name = $validatedData['name'];
		$subject->save();

		return response()->json(['message' => 'Subject updated successfully'], 201);
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
		return response()->json($subjects);
	}
}
