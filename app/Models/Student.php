<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	use HasFactory;

	public function schoolClass()
	{
		return $this->belongsTo(SchoolClass::class, 'class_id');
	}

	public function issueCovers()
	{
		return $this->hasMany(IssueCover::class, 'student_id');
	}
}
