<!--
  // Add in page html
  <head>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
  </head>

  // Add in vite .env file
  VITE_GOOGLE_OAUTH_CLIENT_ID="<GOOGLE-CLIENT_ID>.apps.googleusercontent.com"
-->

<script setup>
import { onMounted, ref } from 'vue'

let user = ref(null)
let clientId = import.meta.env.VITE_GOOGLE_OAUTH_CLIENT_ID
const callback_url = '/oauth/google'

// Get token after login
async function handleCredentialResponse(response) {
	console.log('Encoded JWT ID token: ' + response.credential)
	// Backend token verification here (login user on backend server)
	user.value = await verifyTokenUserInfo(response.credential)
}

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

		// Laravel backend server JWT token validation and user authentication
		const resb = await axios.get(`${callback_url}?id_token=${id_token}`)
		console.log('Logged user detail', resb)
		return resb.data.userinfo
	} catch (err) {
		console.log('Login error', err)
		user.value = null
		return null
	}
}

// Signout user
function onSignout() {
	google.accounts.id.disableAutoSelect()
	// Logout user
	user.value = null
	// Backend redirect logout
	location.href = '/logout'
}

// Revoke google token
function onRevoke(email) {
	google.accounts.id.revoke(email, (done) => {
		console.log(done.error)
	})
}

onMounted(() => {
	window.google.accounts.id.initialize({
		client_id: clientId,
		callback: handleCredentialResponse,
		// itp_support: true,
	})
	google.accounts.id.renderButton(document.getElementById('buttonDiv'), { theme: 'outline', size: 'large' })
	// google.accounts.id.prompt(); // also display the One Tap dialog
	google.accounts.id.prompt((notification) => {
		if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
			// try next provider if OneTap is not displayed or skipped
		}
	})
})
</script>

<template>
	<div class="loggedUser">
		<div v-if="user != null">
			<!-- Logged User -->
			<div class="userImage">
				<img :src="user.picture" />
			</div>
			<div class="userDetails">
				<div class="userName">Hello, {{ user.name }}</div>
				<div class="userEmail">{{ user.email }}</div>
			</div>
			<button v-if="user != null" @click="onSignout" class="logoutButton" title="Logout">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.51428 20H4.51428C3.40971 20 2.51428 19.1046 2.51428 18V6C2.51428 4.89543 3.40971 4 4.51428 4H8.51428V6H4.51428V18H8.51428V20Z" fill="currentColor" />
					<path d="M13.8418 17.385L15.262 15.9768L11.3428 12.0242L20.4857 12.0242C21.038 12.0242 21.4857 11.5765 21.4857 11.0242C21.4857 10.4719 21.038 10.0242 20.4857 10.0242L11.3236 10.0242L15.304 6.0774L13.8958 4.6572L7.5049 10.9941L13.8418 17.385Z" fill="currentColor" />
				</svg>
			</button>
		</div>
		<div v-else>
			<!-- Google SignIn Button -->
			<div id="buttonDiv"></div>
		</div>
	</div>
</template>

<style scoped>
.loggedUser {
	width: auto;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 5px;
	margin: 10px;
	background: #0077ff11;
	border: 1px solid #0077ff;
	border-radius: 10px;
	font-size: 14px;
	color: #222;
}
.userImage {
	float: left;
	width: 40px;
	height: 40px;
	padding: 5px;
	margin-right: 10px;
	border-radius: 50%;
	border: 1px solid #0077ff;
	overflow: hidden;
}
.userImage img {
	float: left;
	width: 100%;
}
.userDetails {
	float: left;
	width: auto;
}
.userName {
	color: #3c4043;
	font-weight: 700;
}
.userEmail {
	color: #73767a;
	font-weight: 400;
}
.logoutButton {
	float: left;
	width: auto;
	padding: 8px;
	color: #0077ff;
	background: transparent;
	border: 0px;
	cursor: pointer;
}
.logoutButton:hover {
	color: #ff2233;
}
</style>
