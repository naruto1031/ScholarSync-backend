<?php

namespace App\Models;

use App\Traits\AuditableCustom;
use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'student_id';
	public $incrementing = false;
	protected $fillable = [
		'student_id',
		'class_id',
		'email',
		'name',
		'registration_number',
		'attendance_number',
	];

	public function schoolClass(): BelongsTo
	{
		return $this->belongsTo(SchoolClass::class, 'class_id');
	}

	public function issueCovers(): HasMany
	{
		return $this->hasMany(IssueCover::class, 'student_id');
	}

	public static function registerNewStudent($data): Student
	{
		$student = new self([
			'student_id' => $data['student_id'],
			'class_id' => $data['class_id'],
			'email' => $data['email'],
			'name' => $data['name'],
			'registration_number' => $data['registration_number'],
			'attendance_number' => $data['attendance_number'],
		]);

		$student->save();

		return $student;
	}

	public static function updateStudent($data): Student
	{
		$student = self::where('student_id', $data['student_id'])->first();
		$student->class_id = $data['class_id'];
		$student->registration_number = $data['registration_number'];
		$student->attendance_number = $data['attendance_number'];
		$student->save();

		return $student;
	}

	public static function deleteStudent($studentId): void
	{
		$student = self::where('student_id', $studentId)->first();
		$student->delete();
	}

	public function getClassDisplayName(): string
	{
		$class = $this->schoolClass;
		$department = $class->department;
		return $department->name . $class->name;
	}
}
