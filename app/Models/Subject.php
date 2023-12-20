<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
	use HasFactory;
	public function teacherSubjects(): HasMany
	{
		return $this->hasMany(TeacherSubject::class, 'subject_id');
	}
}
