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
		'due_date',
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

	public static function registerNewIssue($data): Issue
	{
		$issue = new self([
			'teacher_subject_id' => $data['teacher_subject_id'],
			'name' => $data['name'],
			'due_date' => $data['due_date'],
			'comment' => $data['comment'] ?? '',
			'task_number' => $data['task_number'],
			'private_flag' => $data['private_flag'],
			'challenge_flag' => $data['challenge_flag'] ?? false,
			'challenge_max_score' => $data['challenge_max_score'] ?? 0,
		]);

		$issue->save();

		return $issue;
	}

	public static function updateIssue($data): Issue
	{
		$issue = self::where('issue_id', $data['issue_id'])->first();

		$issue->fill($data);

		$issue->save();

		return $issue;
	}
}
