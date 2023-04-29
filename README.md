# Logowanie za pomocą konta Google (Google signin button)
Funkcja Zaloguj się przez Google umożliwia łatwe i bezpieczne logowanie się w aplikacjach lub usługach innych firm przy użyciu konta Google. Przycisk logowania Google oAuth na stronę internetową www (oAuth2).

## Utwórz swoją aplikację
<https://console.cloud.google.com>

## Dodaj klucz oAuth do swojej aplikacji (pobierz plik json klucza z clientId)
Dodaj adresy uri dla klucza wideo: <https://www.youtube.com/watch?v=EaSWnk5fLdc>
- http://localhost
- http://localhost:8000
- https://your.page.url

## Generator przycisku logowania Google
<https://developers.google.com/identity/gsi/web/tools/configurator?hl=en>

## Przycisk z Javascript Api

Zmień google klient id: <GOOGLE_CLIENT_ID>.apps.googleusercont.com

```html
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
<body>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script>		
    function onSignout() {
      google.accounts.id.disableAutoSelect();
    }
    function onRevoke(uid = '1618033988749895') {
      google.accounts.id.revoke(uid, done => {
        console.log(done.error);
      });
    }
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: " + response.credential);
      // Send to backend server and uthenticate user
      // Confirm jwt token (backend) and get google userinfo from: https://oauth2.googleapis.com/tokeninfo?id_token={id_token}
    }

    window.onload = function () {
      window.google.accounts.id.initialize({
        client_id: "<GOOGLE_CLIENT_ID>.apps.googleusercontent.com",
        callback: handleCredentialResponse,
        itp_support: true
      });
      google.accounts.id.renderButton(
        document.getElementById("buttonDiv"),
        { theme: "outline", size: "large" }
      );
      // google.accounts.id.prompt(); // also display the One Tap dialog
      google.accounts.id.prompt((notification) => {
        if (notification.isNotDisplayed()) {
          console.log(notification.getNotDisplayedReason())
        } else if (notification.isSkippedMoment()) {
          console.log(notification.getSkippedReason())
        } else if(notification.isDismissedMoment()) {
          console.log(notification.getDismissedReason())
        }

        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
          // try next provider if OneTap is not displayed or skipped
        }
      });
    }
  </script>
  <div id="buttonDiv"></div>
</body>
</html>
```

## Przycisk z Html Api

Zmień google klient id: <GOOGLE_CLIENT_ID>.apps.googleusercont.com

```html
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"></meta>
<body>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script>		
    function onSignout() {
      google.accounts.id.disableAutoSelect();
    }
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: " + response.credential);
      // Send to backend server and uthenticate user
      // Confirm jwt token (backend) and get google userinfo from: https://oauth2.googleapis.com/tokeninfo?id_token={id_token}
    }
  </script>

  <div id="g_id_onload" data-client_id="<GOOGLE_CLIENT_ID>.apps.googleusercontent.com" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse" data-nonce="" data-itp_support="true"></div>
  <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="filled_blue" data-text="signin_with," data-size="large" data-logo_alignment="left"></div>
</body>
</html>
```

## JWT token walidacja

Walidacja tokena jwt w js lub na serverze backendowym i pobranie danych userinfo.

```js
// Validate jwt token on your backend server (this is sample with google server)
// Login user on backend server with session
// Return logged user detail from backend here
async function verifyTokenUserInfo(id_token) {
	try {
		// Javascript (tests only)
		// JWT token validation with google server
		const res = await axios.get(`https://oauth2.googleapis.com/tokeninfo?id_token=${id_token}`)
		console.log('Logged user detail', res)
		return res.data
    
    		// Server url
    		const callback_url = '/oauth/google'

		// Laravel backend server JWT token validation with curl request to google url: https://oauth2.googleapis.com/tokeninfo?id_token=
    		// Login user on backend server and return userinfo details
		const resb = await axios.get(`${callback_url}?id_token=${id_token}`)
		console.log('Logged user detail', resb)
		return resb.data.userinfo
	} catch (err) {
		console.log('Login error', err)
		user.value = null
		return null
	}
}
```

## Uruchom server Laravel

Dodaj w google+ api do klucza uri: http://localhost i http://localhost:8000 (Przy błędzie invalid origin ... puste okienko logowania)

```sh
php artisan serve --host=localhost --port=8000
```


## Backend

- JWT walidacja i pobranie google userinfo użytkownika zalogowanego z <https://oauth2.googleapis.com/tokeninfo?id_token={id_token}>
- Zalogowanie użytkownika na backendzie php
- Ustawienie sessji, odesłanie danych zalogowanego usera
- Dokumentacja: <https://developers.google.com/identity/sign-in/web/backend-auth?hl=pl>
