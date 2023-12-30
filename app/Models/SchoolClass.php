<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
	use HasFactory;

	protected $primaryKey = 'class_id';
	protected $fillable = ['department_id', 'name'];

	public function department(): BelongsTo
	{
		return $this->belongsTo(Department::class, 'department_id');
	}

	public function students(): HasMany
	{
		return $this->hasMany(Student::class, 'class_id');
	}

	public function classTeachers(): HasMany
	{
		return $this->hasMany(ClassTeacher::class, 'class_id');
	}

	public function scopeWithDepartment($query)
	{
		return $query->with([
			'department' => function ($query) {
				$query->select('department_id', 'name');
			},
		]);
	}
}
