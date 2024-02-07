<?php

namespace App\Models;

use App\Http\Resources\IssueCoverResource;
use App\Http\Resources\NotSubmittedIssueCoverResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;
use Illuminate\Support\Facades\Log;

class IssueCover extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'issue_cover_id';
	protected $fillable = ['issue_id', 'student_id', 'comment'];

	public function issue(): BelongsTo
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class, 'student_id');
	}

	public function issueCoverStatus(): HasOne
	{
		return $this->hasOne(IssueCoverStatus::class, 'issue_cover_id');
	}

	public static function registerNewIssueCover($data): IssueCover
	{
		$issueCover = new self([
			'issue_id' => $data['issue_id'],
			'student_id' => $data['student_id'],
			'comment' => $data['comment'],
		]);

		$issueCover->save();

		return $issueCover;
	}

	public static function deleteIssueCover(string $issueCoverId): void
	{
		$issueCover = self::where('issue_cover_id', $issueCoverId)->first();
		$issueCover->delete();
	}

	public static function findByStatusesAndStudentId(array $statuses, $studentId)
	{
		$student = Student::with('schoolClass.department')
			->where('student_id', $studentId)
			->firstOrFail();

		$studentClassId = optional($student->schoolClass)->class_id;
		$data = self::select('issue_cover_id', 'issue_id', 'comment')
			->where('student_id', $studentId)
			->whereHas('issueCoverStatus', function ($query) use ($statuses) {
				$query->whereIn('status', $statuses);
			})
			->with(
				'issueCoverStatus',
				'issue.teacherSubject.teacher',
				'issue.teacherSubject.subject',
				'issue.issueClasses'
			)
			->get()
			->map(function ($issueCover) use ($studentClassId) {
				$issueCover->subject_name = optional($issueCover->issue->teacherSubject->subject)->name;
				$issueCover->due_date = optional(
					$issueCover->issue->issueClasses->where('class_id', $studentClassId)->first()
				)->due_date;
				$issueCover->teacher_name = optional($issueCover->issue->teacherSubject->teacher)->name;

				unset(
					$issueCover->issue,
					$issueCover->issueCoverStatus,
					$issueCover->created_at,
					$issueCover->updated_at
				);

				return $issueCover;
			});

		return $data;
	}

	public static function findNotSubmittedByStudentId(string $studentId)
	{
		$submittedIssueIds = self::where('student_id', $studentId)->pluck('issue_id');

		$student = Student::with('schoolClass.department')
			->where('student_id', $studentId)
			->firstOrFail();

		$studentClassId = optional($student->schoolClass)->class_id;

		$issues = Issue::whereNotIn('issue_id', $submittedIssueIds)
			->where('private_flag', 0)
			->with(['teacherSubject.subject', 'issueClasses'])
			->get()
			->map(function ($issue) use ($studentClassId) {
				$issue->subject_name = optional($issue->teacherSubject->subject)->name;
				$issue->due_date = optional(
					$issue->issueClasses->where('class_id', $studentClassId)->first()
				)->due_date;

				unset($issue->teacherSubject, $issue->issueClasses, $issue->created_at, $issue->updated_at);

				return $issue;
			});

		return $issues;
	}

	public static function findBySearchCondition(array $searchData)
	{
		$issueCovers = self::select('issue_cover_id', 'issue_id', 'comment', 'student_id')
			->whereHas('issueCoverStatus', function ($query) use ($searchData) {
				$query->where('status', $searchData['status']);
			})
			->whereHas('student', function ($query) use ($searchData) {
				$query->where('class_id', $searchData['class_id']);
				if (
					isset($searchData['attendance_numbers']) &&
					count($searchData['attendance_numbers']) > 0
				) {
					$query->whereIn('attendance_number', $searchData['attendance_numbers']);
				}
				if (
					isset($searchData['exclude_attendance_numbers']) &&
					count($searchData['exclude_attendance_numbers']) > 0
				) {
					$query->whereNotIn('attendance_number', $searchData['exclude_attendance_numbers']);
				}
			})
			->where('issue_id', $searchData['issue_id'])
			->with('issueCoverStatus', 'student')
			->get();

		return $issueCovers;
	}

	public static function findNotSubmittedByIssueIdAndClassId(array $searchData)
	{
		$submittedStudentIds = self::where('issue_id', $searchData['issue_id'])->pluck('student_id');

		$notSubmittedStudents = Student::where('class_id', $searchData['class_id'])
			->whereNotIn('student_id', $submittedStudentIds)
			->with('schoolClass.department')
			->get();
		return $notSubmittedStudents;
	}

	public static function findIssueCoverByIssueCoverId(array $issueCoverIds)
	{
		$issueCovers = self::whereIn('issue_cover_id', $issueCoverIds)
			->with('issueCoverStatus', 'student')
			->get();

		return $issueCovers;
	}

	public static function updateIssueCoverStatus(
		$issueCoverId,
		$status,
		$evaluation = null,
		$resubmissionDeadline = null,
		$resubmissionComment = null
	) {
		$issueCover = self::find($issueCoverId);
		$issueCover->issueCoverStatus->status = $status;
		if ($evaluation) {
			$issueCover->issueCoverStatus->evaluation = $evaluation;
		}
		if ($resubmissionDeadline) {
			$issueCover->issueCoverStatus->resubmission_deadline = $resubmissionDeadline;
		}

		if ($resubmissionComment) {
			$issueCover->issueCoverStatus->resubmission_comment = $resubmissionComment;
		}
		$issueCover->issueCoverStatus->save();
		return $issueCover;
	}

	public static function updateIssueCover(
		$issue_cover_id,
		$status,
		$evaluation = null,
		$resubmission_deadline = null,
		$resubmission_comment = null,
		$current_score = null
	) {
		$issueCover = self::find($issue_cover_id);
		$issueCover->issueCoverStatus->status = $status;
		if ($evaluation) {
			$issueCover->issueCoverStatus->evaluation = $evaluation;
		}
		if ($resubmission_deadline) {
			$issueCover->issueCoverStatus->resubmission_deadline = $resubmission_deadline;
		}
		if ($resubmission_comment) {
			$issueCover->issueCoverStatus->resubmission_comment = $resubmission_comment;
		}
		if ($current_score !== null) {
			$issueCover->issueCoverStatus->current_score = $current_score;
		}
		$issueCover->issueCoverStatus->save();
		return $issueCover;
	}
}
