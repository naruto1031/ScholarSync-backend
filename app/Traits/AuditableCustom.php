<?php

namespace App\Traits;

trait AuditableCustom
{
	public function transformAudit(array $data): array
	{
		$data['user_id'] = request()->attributes->get('jwt_sub');
		$data['user_type'] = request()->attributes->get('user_type');
		return $data;
	}
}
