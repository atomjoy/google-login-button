<?php

namespace App\Http\Controllers\Oauth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OauthGoogle extends Controller
{
	// Jwt validation url
	protected $url = "https://oauth2.googleapis.com/tokeninfo";

	// Google client id
	protected $clientId = null;

	// Google oauth url
	protected $iss = "https://accounts.google.com";

	function index()
	{
		try {
			// Get google client id from .env
			$this->clientId = env('VITE_GOOGLE_OAUTH_CLIENT_ID');
			// Check key
			if (empty($this->clientId)) {
				throw new Exception('Invalid client id', 422);
			}
			// Check google token
			$response = Http::get($this->url, ["id_token" => request()->input('id_token')]);
			// Check
			if ($response->ok()) {
				// Json string
				$arr = $response->json();
				// Confirm client id
				if ($arr['aud'] != $this->clientId && $arr['iss'] == $this->iss) {
					throw new Exception('Token validation error.', 422);
				}

				// Login user if exists in database
				$user = User::where(['email' => $arr['email']])->first();
				// If user != null
				if ($user) {
					// Login user
					Auth::login($user, true);
					// If logged
					if (Auth::check()) {
						return response()->json([
							'message' => 'Authenticated.',
							'user' => Auth::user(),
							"userinfo" => $arr
						], 200);
					}
				}
			}

			// If not authenticated
			return response()->json([
				'message' => 'Unauthenticated.',
				"response" => $response->json()
			], 422);
		} catch (Exception $e) {
			return response()->json([
				'message' => 'Validation Error.',
			], 422);
		}
	}

	function sampleResponse()
	{
		return [
			"iss" => "https://accounts.google.com",
			"nbf" => "1682627871",
			"aud" => "<GOOGLE-CLIENT_ID>.apps.googleusercontent.com",
			"sub" => "103670191075038866340",
			"email" => "unknown@example.com",
			"email_verified" => "true",
			"azp" => "<GOOGLE-CLIENT_ID>.apps.googleusercontent.com",
			"name" => "Maxiu",
			"picture" => "https://lh3.googleusercontent.com/a/AGNmyxYkSTMH6VDZc8qIPfjgVh4ZHyoFUnXdy4vGKpiqzQ=s96-c",
			"given_name" => "Maxiu",
			"iat" => "1682628171",
			"exp" => "1682631771",
			"jti" => "e8e6280730dfd3abfdc19a1f519a7d5619bd8d53",
			"alg" => "RS256",
			"kid" => "c9afda3682ebf09eb3055c1c4bd39b751fbf8195",
			"typ" => "JWT",
		];
	}
}
