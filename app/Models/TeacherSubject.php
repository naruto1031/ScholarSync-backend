<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherSubject extends Model
{
	use HasFactory;

	public function teacher(): BelongsTo
	{
		return $this->belongsTo(Teacher::class, 'teacher_id');
	}

	public function subject(): BelongsTo
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	public function issues(): HasMany
	{
		return $this->hasMany(Issue::class, 'teacher_subject_id');
	}
}
