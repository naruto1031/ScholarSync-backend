<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class ClassTeacher extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'class_teacher_id';
	protected $fillable = ['class_id', 'teacher_id'];

	public function scopeWithClassAndDepartment($query, $teacher_id)
	{
		return $query->where('teacher_id', $teacher_id)->with([
			'schoolClass' => function ($query) {
				$query->select('class_id', 'name', 'department_id');
			},
			'schoolClass.department' => function ($query) {
				$query->select('department_id', 'name');
			},
		]);
	}

	public function teacher(): BelongsTo
	{
		return $this->belongsTo(Teacher::class, 'teacher_id');
	}

	public function schoolClass(): BelongsTo
	{
		return $this->belongsTo(SchoolClass::class, 'class_id');
	}
}
