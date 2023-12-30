<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class IssueCover extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	public function issue(): BelongsTo
	{
		return $this->belongsTo(Issue::class, 'issue_id');
	}

	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class, 'student_id');
	}

	public function issueCoverStatus(): HasOne
	{
		return $this->hasOne(IssueCoverStatus::class, 'issue_cover_id');
	}

	public static function registerNewIssueCover($data): IssueCover
	{
		$issueCover = new self([
			'issue_id' => $data['issue_id'],
			'student_id' => $data['student_id'],
			'comment' => $data['comment'] ?? '',
		]);

		$issueCover->save();

		return $issueCover;
	}

	public static function deleteIssueCover(string $issueCoverId): void
	{
		$issueCover = self::where('issue_cover_id', $issueCoverId)->first();
		$issueCover->delete();
	}

	public static function findByStatuses(array $statuses)
	{
		return self::whereHas('issueCoverStatus', function ($query) use ($statuses) {
			$query->whereIn('status', $statuses);
		})
			->with('issueCoverStatus')
			->get();
	}
}
