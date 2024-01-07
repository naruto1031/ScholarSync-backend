<?php

namespace App\Models;

use App\Traits\AuditableCustom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Teacher extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'teacher_id';
	public $incrementing = false;

	protected $fillable = ['teacher_id', 'name', 'email'];

	public function classTeachers(): HasMany
	{
		return $this->hasMany(ClassTeacher::class, 'teacher_id');
	}

	public function teacherSubjects(): HasMany
	{
		return $this->hasMany(TeacherSubject::class, 'teacher_id');
	}
}
