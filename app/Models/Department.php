<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class Department extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

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

	public static function getDepartmentList()
	{
		$departments = self::all();
		$departments = $departments->map(function ($department) {
			return [
				'department_id' => $department->department_id,
				'name' => $department->name,
			];
		});
		return $departments;
	}
}
