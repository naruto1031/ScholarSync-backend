<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueCover extends Model
{
	use HasFactory;

	public function issue()
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function student()
	{
		return $this->belongsTo(Student::class, 'student_id');
	}

	public function issueCoverStatus()
	{
		return $this->hasOne(IssueCoverStatus::class, 'issue_cover_id');
	}
}
