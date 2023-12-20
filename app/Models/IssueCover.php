<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class IssueCover extends Model
{
	use HasFactory;

	public function issue(): BelongsTo
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class, 'student_id');
	}

	public function issueCoverStatus(): HasOne
	{
		return $this->hasOne(IssueCoverStatus::class, 'issue_cover_id');
	}
}
