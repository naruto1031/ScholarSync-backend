<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
	use HasFactory;

	protected $primaryKey = 'teacher_id';

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
