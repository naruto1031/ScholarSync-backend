<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
	use HasFactory;

	protected $primaryKey = 'issue_id';
	protected $fillable = [
		'teacher_subject_id',
		'name',
		'due_date',
		'comment',
		'task_number',
		'private_flag',
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
}
