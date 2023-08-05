<?php
namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class VerifyAzureJwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        // $token = $request->bearerToken();
        $request->headers->set('Authorization', 'Bearer ' . 'jwt access token');
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }
        
          // JWTのヘッダを取得
          $tks = explode('.', $token);
          $headb64 = $tks[0];
          $header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64));
          $kid = $header->kid;  // 署名に使用されたキーのIDを取得
          $jwks_url = 'https://login.microsoftonline.com/tenat-id/discovery/v2.0/keys?appid=client-id';
          $jwks = json_decode(file_get_contents($jwks_url), true);
          $secrets = JWK::parseKeySet($jwks, "RS256");

          if (!isset($secrets[$kid])) {
              return response()->json(['message' => 'Public key not found'], 500);
          }
  
          $publicKey = $secrets[$kid];
          try {
            // Assuming $publicKey is already in the correct format
            $decode = JWT::decode("eyJ0eXAiOiJKV1QiLCJub25jZSI6ImZZb09rMU03WUU1MDF3R3Y3Q0tMSV9jWlo4c0U0enpRY2dzeENoV0VmZlEiLCJhbGciOiJSUzI1NiIsIng1dCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyIsImtpZCI6Ii1LSTNROW5OUjdiUm9meG1lWm9YcWJIWkdldyJ9.eyJhdWQiOiIwMDAwMDAwMy0wMDAwLTAwMDAtYzAwMC0wMDAwMDAwMDAwMDAiLCJpc3MiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8wNTkzMWYyYi0xN2ZmLTQyNjEtOGU0ZS0wZGU0YjE1NzBiODQvIiwiaWF0IjoxNjkxMDcxMDIyLCJuYmYiOjE2OTEwNzEwMjIsImV4cCI6MTY5MTA3NTUzMiwiYWNjdCI6MCwiYWNyIjoiMSIsImFpbyI6IkFZUUFlLzhVQUFBQVA3TUdjQ293OHNmc00wc2VmQ25xcmxqZXJPTWg5a0Z1ZW5zQjU0WEd5VXJzKzZiRjRwVm8zTTNGeldvUEg1dE1JdHJLbWJ3UVZkc24xRmZBNXkvTFZDaUNMNUswV0RTb09GWngrbStKdUxDdXkwbGYwYU5TTy9kS3lobGJoNC94M2VVdmkzMVcraDVvRG9vaVkwUFZnVVJaeHAyNE9KYTAxZzRuUnQ0dHNJdz0iLCJhbHRzZWNpZCI6IjE6bGl2ZS5jb206MDAwMzAwMDA1Rjk3RjUyMCIsImFtciI6WyJwd2QiLCJtZmEiXSwiYXBwX2Rpc3BsYXluYW1lIjoic21hbmUtYXV0aCIsImFwcGlkIjoiYzZmMzU0NDgtOTI3Ny00NTUyLWEzZWUtMGQ5YzA0OTJjZmExIiwiYXBwaWRhY3IiOiIxIiwiZW1haWwiOiJrb25kb25hcnV0bzA4M0BnbWFpbC5jb20iLCJmYW1pbHlfbmFtZSI6Iui_keiXpCIsImdpdmVuX25hbWUiOiLmiJDkuroiLCJpZHAiOiJsaXZlLmNvbSIsImlkdHlwIjoidXNlciIsImlwYWRkciI6IjI0MGY6M2I6Y2MxOToxOjU4NGQ6N2Q1Yjo3NzMwOjc2NzAiLCJuYW1lIjoi6L-R6JekIOaIkOS6uiIsIm9pZCI6ImNjZjFkNjAwLTIwN2UtNGIyMy05MGI4LWU3YjI0ODA3NDNjZCIsInBsYXRmIjoiMyIsInB1aWQiOiIxMDAzMjAwMkEwMDAyMEVBIiwicmgiOiIwLkFXb0FLeC1UQmY4WFlVS09UZzNrc1ZjTGhBTUFBQUFBQUFBQXdBQUFBQUFBQUFCcUFPay4iLCJzY3AiOiJlbWFpbCBvcGVuaWQgcHJvZmlsZSBVc2VyLlJlYWQiLCJzaWduaW5fc3RhdGUiOlsia21zaSJdLCJzdWIiOiJmVjlZZmd5UHo2VUNhOERwbzZ0YmpueG55Y2sxX1FmdHdWdW0ybFA0Z0FFIiwidGVuYW50X3JlZ2lvbl9zY29wZSI6IkpQUiIsInRpZCI6IjA1OTMxZjJiLTE3ZmYtNDI2MS04ZTRlLTBkZTRiMTU3MGI4NCIsInVuaXF1ZV9uYW1lIjoibGl2ZS5jb20ja29uZG9uYXJ1dG8wODNAZ21haWwuY29tIiwidXRpIjoiQW5ScEU5RlFra20wWEZzUWthVUdBQSIsInZlciI6IjEuMCIsIndpZHMiOlsiNjJlOTAzOTQtNjlmNS00MjM3LTkxOTAtMDEyMTc3MTQ1ZTEwIiwiYjc5ZmJmNGQtM2VmOS00Njg5LTgxNDMtNzZiMTk0ZTg1NTA5Il0sInhtc19zdCI6eyJzdWIiOiJQLUFPb08yTHNUZ2JMOHVOczZ3YTNnVVVkbzM2UWR6Y1dtYkJWRlR0WGtBIn0sInhtc190Y2R0IjoxNjg0MTk4MzgxfQ.Isk4GRHyuByYi-mYopkbp5jPCrSERFl9l1yfoL4a6Eq7BheKebJpd5Xr7Gt2t17wp4U8WGu7bnAvh2PNr6D82OKWDpbhscdmeggQdDTvCfztLywQY7vqFE1iHlafcVT2TQ2IiCkINKhxECwTFmf6dKstrEVwFJWe22_d0w58OYhXjulkyqCGMZJIJORGSFxJpAU5FdLgNyIrmhJRrX6cCjj1ukvPQcqjFRAzk9e_rqHWoOH4Er7f6NLNsXIANyWO9T0zNABrvWXLheAgcbN5SwZBrMhdyJd8jQ9h_pvUnynRjIPd1fx2PD2wP5a35vXPGZJJzNdj5OH9Fo5MZB5opg", $publicKey);
        }  catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}