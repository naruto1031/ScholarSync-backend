<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
	use HasFactory;

	public function department()
	{
		return $this->belongsTo(Department::class, 'department_id');
	}

	public function students()
	{
		return $this->hasMany(Student::class, 'class_id');
	}

	public function classTeachers()
	{
		return $this->hasMany(ClassTeacher::class, 'class_id');
	}
}
