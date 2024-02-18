<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class Issue extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'issue_id';
	protected $fillable = [
		'teacher_subject_id',
		'name',
		'comment',
		'task_number',
		'private_flag',
		'challenge_flag',
		'challenge_max_score',
	];

	public function teacherSubject(): BelongsTo
	{
		return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id');
	}

	public function issueDepartments(): HasMany
	{
		return $this->hasMany(IssueDepartment::class, 'issue_id');
	}

	public function issueCovers(): HasMany
	{
		return $this->hasMany(IssueCover::class, 'issue_id');
	}

	public function issueClasses(): HasMany
	{
		return $this->hasMany(IssueClass::class, 'issue_id');
	}

	public static function registerNewIssue($data): Issue
	{
		$issue = new self([
			'teacher_subject_id' => $data['teacher_subject_id'],
			'name' => $data['name'],
			'comment' => $data['comment'] ?? '',
			'task_number' => $data['task_number'],
			'private_flag' => $data['private_flag'],
			'challenge_flag' => $data['challenge_flag'] ?? false,
			'challenge_max_score' => $data['challenge_max_score'] ?? 0,
		]);

		$issue->save();

		return $issue;
	}

	public static function findIssueByTeacherSubjectId(string $teacherSubjectId)
	{
		$issues = self::where('teacher_subject_id', $teacherSubjectId)
			->with('issueClasses.schoolClass')
			->get();

		return $issues;
	}

	public static function updateIssue($data): Issue
	{
		$issue = self::where('issue_id', $data['issue_id'])->first();

		// 受け取ったデータのキーごとに存在チェックを行う
		$updateData = [];
		$keys = [
			'name',
			'comment',
			'task_number',
			'private_flag',
			'challenge_flag',
			'challenge_max_score',
		];
		foreach ($keys as $key) {
			if (isset($data[$key])) {
				$updateData[$key] = $data[$key];
			}
		}

		// 存在するキーのみでモデルを更新
		$issue->fill($updateData);

		$issue->save();

		return $issue;
	}

	public static function findBySubjectId(string $subjectId)
	{
		$issues = self::join(
			'teacher_subjects',
			'issues.teacher_subject_id',
			'=',
			'teacher_subjects.teacher_subject_id'
		)
			->where('teacher_subjects.subject_id', $subjectId)
			->select('issues.*')
			->get();

		return $issues;
	}
}
