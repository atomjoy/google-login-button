# Laravel google login button callback
Google login button callback controller for Laravel

## Routes

routes.php

```php
<?php

// Oauth jwt google login button callback
Route::get('/oauth/google', [OauthGoogle::class, 'index'])->name('oauth.google');
```
