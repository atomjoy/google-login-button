# Google przycisk logowania
Przycisk logowania przez google oauth na stronę internetową www (oAuth2).

## Utwórz swoją aplikację
<https://console.cloud.google.com>

## Dodaj klucz oAuth do swojej aplikacji (pobierz plik json klucza z clientId)
Dodaj adresy uri dla klucza
- http://localhost
- http://localhost:8000

## Generator przycisku logowania Google
<https://developers.google.com/identity/gsi/web/tools/configurator?hl=en>

## Przycisk z Javascript Api

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

## Uruchom server Laravel

Dodaj w google+ api do klucza uri: http://localhost i http://localhost:8000 (Przy błędzie invalid origin ... puste okienko logowania)

```sh
php artisan serve --host=localhost --port=8000
```


## Backend

- JWT walidacja i pobranie google userinfo użytkownika zalogowanego z <https://oauth2.googleapis.com/tokeninfo?id_token={id_token}>
- Zalogowanie użytkownika na backendzie php
- Ustawienie sessji, odesłanie danych zalogowanego usera
