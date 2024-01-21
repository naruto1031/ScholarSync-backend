<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class SubjectDepartment extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'subject_department_id';
	protected $fillable = ['subject_id', 'department_id'];

	public function subject()
	{
		return $this->belongsTo(Subject::class, 'subject_id');
	}

	public function department()
	{
		return $this->belongsTo(Department::class, 'department_id');
	}

	public static function registerNewSubjectDepartment($data): SubjectDepartment
	{
		// department_idsが配列で渡されるので、それぞれに対して登録処理を行う
		$subjectDepartment = new self([
			'subject_id' => $data['subject_id'],
			'department_id' => $data['department_id'],
		]);

		$subjectDepartment->save();

		return $subjectDepartment;
	}
}
