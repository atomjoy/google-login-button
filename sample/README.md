# Niestandardowy przycisk logowania Google w Vue3

Przycisk logowania przez google oauth na stronę internetową www (oAuth2) w vue3 z przekierowaniem na backend.

## Google Login Button url generator

```js
// Vue Google login button custom url for redirects with callback
// Add in .env file
// # Redirect after google authorization to backend
// VITE_GOOGLE_OAUTH_REDIRECT="http://localhost:8000/oauth/google"
// # Google clientid
// VITE_GOOGLE_OAUTH_CLIENT_ID="<GOOGLE_CLIENT_ID>..."

export const getGoogleLoginButtonUrl = (from) => {
  const rootUrl = `https://accounts.google.com/o/oauth2/v2/auth`

  const options = {
    redirect_uri: import.meta.env.VITE_GOOGLE_OAUTH_REDIRECT,
    client_id: import.meta.env.VITE_GOOGLE_OAUTH_CLIENT_ID,
    prompt: 'consent',
    scope: ['https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email'].join(' '),
    state: from,
    // Show the account selector and aproval ui
    // prompt: 'select_account consent'

    // Get with access_token
    response_type: 'token',
    access_type: 'online',
    // Backend get userinfo details 
    // Create GET request add bearer auth header with access_token and send to:
    // `https://www.googleapis.com/oauth2/v2/userinfo`
    // `https://www.googleapis.com/oauth2/v2/userinfo?alt=json&access_token=${access_token}`
    
    // Get with jwt id_token		
    // response_type: 'id_token',
    // access_type: 'online',
    // nonce: 'random_string',
    // Backend get userinfo from uri: https://oauth2.googleapis.com/tokeninfo?id_token={id_token}

    // Get with code		
    // response_type: 'code',
    // access_type: 'offline',
    // Backend get access_token request method POST to: `https://oauth2.googleapis.com/token`
    // Then send access_token to userinfo url `https://www.googleapis.com/oauth2/v2/userinfo`
  }

  const query = new URLSearchParams(options)
  return `${rootUrl}?${query.toString()}`
}
```

## Php Google login button
Jak w php utworzyć przycisk logowania google i pobierać informacje o użytkowniku.

### Link do przekierowania usera

```php
$google_login_button_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email) . '&redirect_uri=' . urlencode(GOOGLE_REDIRECT_URL) . '&response_type=code&client_id=' . GOOGLE_ID . '&access_type=online';
```

### Pobieranie access_token z parametrem code

```php
<?php

// Get access_token from code POST method
$url = 'https://accounts.google.com/o/oauth2/token';

// Send data
$curlPostData = 'client_id='.GOOGLE_ID.'&redirect_uri='.urlencode(GOOGLE_REDIRECT_URL).'&client_secret='.GOOGLE_SECRET.'&code='.$_GET['code'].'&grant_type=authorization_code';
```

### Pobieranie userinfo z access_token i nagłówkiem bearer

```php
// Headers
$header = 'Authorization: Bearer ' . $data['access_token'];

// Url
$url = 'https://www.googleapis.com/oauth2/v2/userinfo';
$url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=email,verified_email';
```

### Dokumentacja, przykłady
- https://developers.google.com/identity/protocols/oauth2/web-server?hl=pl#httprest_2
- https://github.com/kamilwyremski/logowanie-google/blob/master/index.php
