<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class IssueClass extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	protected $primaryKey = 'issue_class_id';
	protected $fillable = ['issue_id', 'class_id', 'due_date'];

	public function issue(): BelongsTo
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function schoolClass(): BelongsTo
	{
		return $this->belongsTo(SchoolClass::class, 'class_id');
	}

	public static function registerNewIssueClass($data): IssueClass
	{
		$issueClass = new self([
			'issue_id' => $data['issue_id'],
			'class_id' => $data['class_id'],
			'due_date' => $data['due_date'],
		]);

		$issueClass->save();

		return $issueClass;
	}

	public static function updateIssueClass($data): IssueClass
	{
		$issueClass = self::where('issue_class_id', $data['issue_class_id'])->first();
		$issueClass->issue_id = $data['issue_id'];
		$issueClass->class_id = $data['class_id'];
		$issueClass->due_date = $data['due_date'];

		$issueClass->save();

		return $issueClass;
	}
}
