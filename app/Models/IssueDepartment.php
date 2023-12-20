<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueDepartment extends Model
{
	use HasFactory;

	public function issue()
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function department()
	{
		return $this->belongsTo(Department::class, 'department_id');
	}
}
