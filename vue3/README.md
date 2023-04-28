# Vue3 google login button
Przykład logowania z użyciem Google login button (tylko javascript bez servera backend).

## Dodaj skrypt do nagłówka strony
```js
<head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
```

## Dodaj google client id

Dodaj w pliku .env google client id

```vue
VITE_GOOGLE_OAUTH_CLIENT_ID="<GOOGLE-CLIENT_ID>.apps.googleusercontent.com"
```


## Router

router.js

```vue
{
  path: '/google-button',
  name: 'google.button',
  component: () => import('../views/google/GoogleLoginButton.vue'),
},
```

## Backend Laravel

### Usuń lub skomentuj w GoogleloginButton.vue

```js
// Javascript (tests only)
// JWT token validation with google server
// const res = await axios.get(`https://oauth2.googleapis.com/tokeninfo?id_token=${id_token}`)
// console.log('Logged user detail', res)
// return res.data
```

### Zainstaluj kontroler, route i migracje z katalogu laravel

- Readme w <https://github.com/atomjoy/google-login-button/tree/main/laravel>
- Create user in database with your google account email address.

## Screenshots
<img src="https://raw.githubusercontent.com/atomjoy/google-login-button/main/vue3/vue3-google-login.png" width="100%">
<img src="https://raw.githubusercontent.com/atomjoy/google-login-button/main/vue3/vue3-google-logged-user.png" width="100%">
