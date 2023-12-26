<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
	use HasFactory;

	protected $primaryKey = 'department_id';
	protected $fillable = ['name'];

	public function schoolClasses(): HasMany
	{
		return $this->hasMany(SchoolClass::class, 'department_id');
	}

	public function issueDepartments(): HasMany
	{
		return $this->hasMany(IssueDepartment::class, 'department_id');
	}
}
