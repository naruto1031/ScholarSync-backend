<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
	public function handle(Request $request, Closure $next): Response
	{
		if (env('APP_ENV') === 'local') {
			$request->attributes->add(['user_type' => 'teacher']);
			return $next($request);
		}

		$userGroups = $request->attributes->get('user_groups', []);

		if (!in_array(env('TEACHER_GROUP_ID'), $userGroups)) {
			return response()->json(['message' => 'Access denied'], 403);
		}
		$request->attributes->add(['user_type' => 'teacher']);

		return $next($request);
	}
}
