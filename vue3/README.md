# Vue3 google login button

## Dodaj skrypt do nagłówka strony
```js
<head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
```

## Zmień clientId w GoogleLoginButton.vue

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
