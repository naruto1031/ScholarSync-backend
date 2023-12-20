<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueCoverStatus extends Model
{
	use HasFactory;

	public function issueCover(): BelongsTo
	{
		return $this->belongsTo(IssueCover::class, 'issue_cover_id');
	}
}
