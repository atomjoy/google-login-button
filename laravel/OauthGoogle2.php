<?php

namespace App\Http\Controllers\Oauth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;

/**
 * Add in .env file
 *
 * VITE_GOOGLE_OAUTH_CLIENT_ID="<GOOGLE-CLIENT-ID>"
 *
 * Required packages:
 * composer require google/apiclient:^2.13.2,
 *
 * Table users add columns:
 * $table->remeberToken();
 * $table->string('oauth_provider', 100)->default('local');
 */
class OauthGoogle2 extends Controller
{
	// Google client id
	protected $clientId = null;

	// Google http jwt token validation url
	protected $tokenUrl = "https://oauth2.googleapis.com/tokeninfo";

	// Google oauth url
	protected $iss = "https://accounts.google.com";

	/**
	 * Enable google client id_token validation
	 *
	 * Required packages:
	 * composer require google/apiclient:^2.13.2,
	 */
	protected $validateWithGoogleClient = false;

	function index()
	{
		try {
			// Get google client id from .env
			$this->clientId = env('VITE_GOOGLE_OAUTH_CLIENT_ID');
			// Check key
			if (empty($this->clientId)) {
				return response()->json([
					'message' => 'Empty .env client_id.',
					"response" => null,
				], 422);
			}
			// JWT token
			$id_token = request()->input('id_token');
			// Check token
			if (empty($id_token)) {
				return response()->json([
					'message' => 'Empty id_token.',
					"response" => null,
				], 422);
			}
			// Validate jwt id_token with google client
			try {
				$this->validateJwt($this->clientId, $id_token);
			} catch (Exception $e) {
				return response()->json([
					'message' => 'Invalid jwt id_token.',
					"response" => null,
				], 422);
			}
			// Check google token with http request
			$response = Http::get($this->tokenUrl, ["id_token" => $id_token]);
			// If status 200
			if ($response->ok()) {
				// Get array from response
				$arr = $response->json();
				// Check token details
				if ($arr['aud'] != $this->clientId || $arr['iss'] != $this->iss) {
					return response()->json([
						'message' => 'Invalid jwt id_token details.',
						"response" => $response->json(),
					], 422);
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
				} else {
					// Log invalid request (optional)
				}
			}
			// If not authenticated
			return response()->json([
				'message' => 'Unauthenticated.',
				"response" => $response->json(),
			], 422);
		} catch (Exception $e) {
			report($e);
			return response()->json([
				'message' => 'Validation Error.',
				"response" => null,
			], 422);
		}
	}

	function validateJwt($clientId, $id_token)
	{
		if ($this->validateWithGoogleClient) {
			$client = new GoogleClient(['client_id' => $clientId]);
			$payload = $client->verifyIdToken($id_token);
			if ($payload) {
				$userid = $payload['sub'];
				return $userid;
			} else {
				throw new Exception('Invalid jwt id_token.', 422);
			}
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
