<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
	use HasFactory;

	public function teacherSubject()
	{
		return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id');
	}

	public function issueDepartments()
	{
		return $this->hasMany(IssueDepartment::class, 'issue_id');
	}

	public function issueCovers()
	{
		return $this->hasMany(IssueCover::class, 'issue_id');
	}
}
