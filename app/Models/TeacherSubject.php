<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
	use HasFactory;

	public function teacher()
	{
		return $this->belongsTo(Teacher::class, 'teacher_id');
	}

	public function subject()
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	public function issues()
	{
		return $this->hasMany(Issue::class, 'teacher_subject_id');
	}
}
