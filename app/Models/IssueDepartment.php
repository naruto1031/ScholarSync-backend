<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueDepartment extends Model
{
	use HasFactory;
	protected $primaryKey = 'issue_department_id';
	protected $fillable = ['issue_id', 'department_id'];

	public function issue(): BelongsTo
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function department(): BelongsTo
	{
		return $this->belongsTo(Department::class, 'department_id');
	}
}
