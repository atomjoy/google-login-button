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
