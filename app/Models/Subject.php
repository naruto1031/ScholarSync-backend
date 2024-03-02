<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Subject extends Model
{
	use HasFactory;
	protected $primaryKey = 'subject_id';
	protected $fillable = ['name'];

	public function teacherSubjects(): HasMany
	{
		return $this->hasMany(TeacherSubject::class, 'subject_id');
	}

	public function subjectDepartments(): HasMany
	{
		return $this->hasMany(SubjectDepartment::class, 'subject_id');
	}

	public static function registerNewSubject($data): Subject
	{
		$subject = new self([
			'name' => $data['name'],
		]);

		$subject->save();

		return $subject;
	}

	public static function updateSubject($data): Subject
	{
		$subject = self::where('subject_id', $data['subject_id'])->first();
		$subject->name = $data['name'];

		$subject->save();

		return $subject;
	}

	public static function findSubjectListByClassId(string $classId)
	{
		$schoolClass = SchoolClass::find($classId);
		if (!$schoolClass) {
			return [];
		}
		$departmentId = $schoolClass->department_id;
		$subjects = self::join(
			'subject_departments',
			'subjects.subject_id',
			'=',
			'subject_departments.subject_id'
		)
			->where('subject_departments.department_id', $departmentId)
			->select('subjects.*')
			->get();

		return $subjects;
	}

	public static function findSubjectsByStudentId(string $studentId)
	{
		return self::whereHas('subjectDepartments', function ($query) use ($studentId) {
			$query->whereIn(
				'department_id',
				Department::whereHas('schoolClasses.students', function ($subQuery) use ($studentId) {
					$subQuery->where('student_id', $studentId);
				})->pluck('department_id')
			);
		})->get();
	}
}
