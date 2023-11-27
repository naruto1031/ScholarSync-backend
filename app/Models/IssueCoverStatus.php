<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueCoverStatus extends Model
{
	use HasFactory;

	public function issueCover()
	{
		return $this->belongsTo(IssueCover::class, 'issue_cover_id');
	}
}
