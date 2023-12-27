<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Student extends Model implements Auditable
{
	use HasFactory;
	use \OwenIt\Auditing\Auditable;

	public function transformAudit(array $data): array
	{
		$data['user_id'] = request()->attributes->get('jwt_sub');
		// TODO: 誰が変更したかを格納する
		$data['user_type'] = 'student';
		return $data;
	}

	protected $primaryKey = 'student_id';
	protected $fillable = [
		'student_id',
		'class_id',
		'email',
		'name',
		'registration_number',
		'attendance_number',
	];

	public function schoolClass(): BelongsTo
	{
		return $this->belongsTo(SchoolClass::class, 'class_id');
	}

	public function issueCovers(): HasMany
	{
		return $this->hasMany(IssueCover::class, 'student_id');
	}
}
