<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function register(Request $request): JsonResponse
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6',
		]);

		$user = User::create([
			'name' => $validatedData['name'],
			'email' => $validatedData['email'],
			'password' => Hash::make($validatedData['password']),
		]);

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $token,
			'token_type' => 'Bearer',
		]);
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function login(Request $request): JsonResponse
	{
		$validatedData = $request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		if (!Auth::attempt($validatedData)) {
			return response()->json(['message' => 'Unauthorized'], 401);
		}

		$user = User::where('email', $validatedData['email'])->firstOrFail();

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'access_token' => $token,
			'token_type' => 'Bearer',
		]);
	}

	/**
	 * @return JsonResponse
	 */
	public function hello(): JsonResponse
	{
		return response()->json([
			'greeting' => 'hello',
		]);
	}
}
