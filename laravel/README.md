# Laravel google login button kontroler
Google login button kontroler w Laravel.

## Settings
Dodaj google client id w pliku .env

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

Remember me w Laravel aktualizacja tabeli user

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
				$table->string('oauth_provider', 100)->nullable()->default(null);
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

### Migrate

```sh
php artisan migrate
```

### Users table

Utwórz w tabeli users użytkownika z adresem email konta Google z którego będziesz się zalogujesz !!!
