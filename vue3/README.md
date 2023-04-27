# Vue3 google login button
Przykład logowania z użyciem Google login button.

## Dodaj skrypt do nagłówka strony
```js
<head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
```

## Zmień clientId 

Zmień google client id w komponencie GoogleLoginButton.vue

```vue
let clientId = '<GOOGLE-CLIENT-ID>.apps.googleusercontent.com'
```


## Router sample

router.js

```vue
{
  path: '/google-button',
  name: 'google.button',
  component: () => import('../views/google/GoogleLoginButton.vue'),
},
```

## Screenshots
<img src="https://raw.githubusercontent.com/atomjoy/google-login-button/main/vue3/vue3-google-login.png" width="100%">
<img src="https://raw.githubusercontent.com/atomjoy/google-login-button/main/vue3/vue3-google-logged-user.png" width="100%">
