# Laravel google login button callback
Google login button callback controller for Laravel.

## Settings
Create google client id w .env

```env
VITE_GOOGLE_OAUTH_CLIENT_ID="<GOOGLE-CLIENT_ID>.apps.googleusercontent.com"
```

## Routes

routes.php

```php
<?php
use App\Http\Controllers\Oauth\OauthGoogle;
use Illuminate\Support\Facades\Route;

// Oauth jwt google login button callback
Route::get('/oauth/google', [OauthGoogle::class, 'index'])->name('oauth.google');
```

## Database

Laravel remeber_me the table columns.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function ($table) {
			if (!Schema::hasColumn('users', 'remember_token')) {
				$table->rememberToken(); // remember_token
			}

			if (!Schema::hasColumn('users', 'oauth_provider')) {
				$table->string('oauth_provider', 100)->nullable()->default('local');
			}
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('users', function ($table) {
			$table->dropColumn(['remember_token', 'oauth_provider']);
		});
	}
};
```
