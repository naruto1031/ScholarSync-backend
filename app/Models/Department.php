<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
	use HasFactory;

	public function schoolClasses()
	{
		return $this->hasMany(SchoolClass::class, 'department_id');
	}

	public function issueDepartments()
	{
		return $this->hasMany(IssueDepartment::class, 'department_id');
	}
}
