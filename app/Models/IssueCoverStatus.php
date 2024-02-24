<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use App\Traits\AuditableCustom;

class IssueCoverStatus extends Model implements AuditableContract
{
	use HasFactory;
	use Auditable, AuditableCustom {
		AuditableCustom::transformAudit insteadof Auditable;
	}

	const STATUS_PENDING = 'pending'; // 課題表紙承認待ち
	const STATUS_REJECTED = 'rejected'; // 課題表紙受け取り拒否
	const STATUS_RESUBMISSION = 'resubmission'; //　課題表紙再提出
	const STATUS_APPROVED = 'approved'; // 課題表紙承認済み
	const STATUS_PENDING_ABSENCE = 'pending_absence'; // 公欠申請中
	const STATUS_ABSENCE = 'absence'; // 公欠申請済み
	const STATUS_PENDING_EXEMPTION_APPROVAL = 'pending_exemption_approval'; // 免除申請許可待ち
	const STATUS_PENDING_EXEMPTION = 'pending_exemption'; // 免除申請中
	const STATUS_EXEMPTION = 'exemption'; // 免除申請済み
	const STATUS_LATE_PENDING = 'late_pending'; // 課題表紙遅延承認待ち

	protected $primaryKey = 'issue_cover_status_id';
	protected $fillable = [
		'issue_cover_id',
		'resubmission_deadline',
		'resubmission_comment',
		'current_score',
		'status',
		'evaluation',
	];

	public function issueCover(): BelongsTo
	{
		return $this->belongsTo(IssueCover::class, 'issue_cover_id');
	}

	public function setStatus($value)
	{
		if (!in_array($value, $this->allowedStatuses())) {
			throw new \InvalidArgumentException("Invalid status: {$value}");
		}

		$this->attributes['status'] = $value;
	}

	public function allowedStatuses()
	{
		return [
			self::STATUS_PENDING,
			self::STATUS_REJECTED,
			self::STATUS_RESUBMISSION,
			self::STATUS_APPROVED,
			self::STATUS_PENDING_ABSENCE,
			self::STATUS_ABSENCE,
			self::STATUS_PENDING_EXEMPTION,
			self::STATUS_EXEMPTION,
			self::STATUS_LATE_PENDING,
			self::STATUS_PENDING_EXEMPTION_APPROVAL,
		];
	}

	public static function registerNewIssueCoverStatus($data): IssueCoverStatus
	{
		$issueCoverStatus = new self([
			'issue_cover_id' => $data['issue_cover_id'],
			'resubmission_deadline' => $data['resubmission_deadline'] ?? null,
			'resubmission_comment' => $data['resubmission_comment'] ?? null,
			'current_score' => $data['current_score'] ?? null,
			'status' => $data['status'] ?? self::STATUS_PENDING,
			'evaluation' => $data['evaluation'] ?? null,
		]);

		$issueCoverStatus->save();

		return $issueCoverStatus;
	}

	public static function updateIssueCoverStatus($data): IssueCoverStatus
	{
		$issueCoverStatus = self::where('issue_cover_id', $data['issue_cover_id'])->first();

		$issueCoverStatus->fill($data);

		$issueCoverStatus->save();

		return $issueCoverStatus;
	}
}
