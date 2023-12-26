<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
	public function handle(Request $request, Closure $next): Response
	{
		if (env('APP_ENV') === 'local') {
			return $next($request);
		}

		$userGroups = $request->attributes->get('user_groups', []);

		if (!in_array(env('STUDENT_GROUP_ID'), $userGroups)) {
			return response()->json(['message' => 'Access denied'], 403);
		}

		return $next($request);
	}
}
