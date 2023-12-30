<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class IssueDepartment extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

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

	public static function registerNewIssueDepartment($data): IssueDepartment
	{
		$issueDepartment = new self([
			'issue_id' => $data['issue_id'],
			'department_id' => $data['department_id'],
		]);

		$issueDepartment->save();

		return $issueDepartment;
	}
}
