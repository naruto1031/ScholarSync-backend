<?php
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class VerifyAzureJwtMiddleware
{
	public function handle($request, Closure $next, $guard = null)
	{
		if (env('APP_ENV') === 'local') {
			$request->attributes->add(['jwt_sub' => 'P-AOoO2LsTgbL8uNs6wa3gUUdo36QdzcWmbBVFTtXk']);
			return $next($request);
		}
		$token = $request->bearerToken();

		if (!$token) {
			return response()->json(['message' => 'Token not provided'], 401);
		}

		// JWTのヘッダを取得
		$tks = explode('.', $token);
		$headb64 = $tks[0];
		$header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64));
		$kid = $header->kid; // 署名に使用されたキーのIDを取得
		$tenantId = env('AZURE_AD_TENANT_ID');
		$jwks_url = "https://login.microsoftonline.com/{$tenantId}/discovery/v2.0/keys";
		$jwks = json_decode(file_get_contents($jwks_url), true);
		$secrets = JWK::parseKeySet($jwks, 'RS256');

		if (!isset($secrets[$kid])) {
			return response()->json(['message' => 'Public key not found'], 500);
		}

		$publicKey = $secrets[$kid];
		try {
			$decode = JWT::decode($token, $publicKey);
			$request->attributes->add(['jwt_sub' => $decode->sub]);
		} catch (\Exception $e) {
			return response()->json(['message' => $e->getMessage()], 500);
		}
		return $next($request);
	}
}
