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

	public static function findByStatusesAndStudentId(array $statuses, $student_id)
	{
		$data = self::select('issue_cover_id', 'issue_id', 'comment')
			->where('student_id', $student_id)
			->whereHas('issueCoverStatus', function ($query) use ($statuses) {
				$query->whereIn('status', $statuses);
			})
			->with('issueCoverStatus', 'issue.teacherSubject.teacher', 'issue.teacherSubject.subject')
			->get();

		return $data;
	}

	public static function findNotSubmittedByStudentId($studentId)
	{
		$submittedIssueIds = self::where('student_id', $studentId)->pluck('issue_id');

		$student = Student::with('schoolClass.department')
			->where('student_id', $studentId)
			->firstOrFail();

		$studentClassId = optional($student->schoolClass)->class_id;

		$issues = Issue::whereNotIn('issue_id', $submittedIssueIds)
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
}
