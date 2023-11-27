<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
	use HasFactory;

	public function classTeachers()
	{
		return $this->hasMany(ClassTeacher::class, 'teacher_id');
	}

	public function teacherSubjects()
	{
		return $this->hasMany(TeacherSubject::class, 'teacher_id');
	}
}
