<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class TeacherSubject extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'teacher_subject_id';
	protected $fillable = ['teacher_id', 'subject_id', 'academic_year'];

	public function teacher(): BelongsTo
	{
		return $this->belongsTo(Teacher::class, 'teacher_id');
	}

	public function subject(): BelongsTo
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	public function issues(): HasMany
	{
		return $this->hasMany(Issue::class, 'teacher_subject_id');
	}

	public function scopeWithSubject($query, $teacher_id)
	{
		return $query->where('teacher_id', $teacher_id)->with([
			'subject' => function ($query) {
				$query->select('subject_id', 'name');
			},
		]);
	}

	public static function registerSubjects(string $teacherId, array $teacherSubject)
	{
		$teacherSubject = new self([
			'teacher_id' => $teacherId,
			'subject_id' => $teacherSubject,
		]);

		$teacherSubject->save();
	}
}
