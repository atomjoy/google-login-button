<script setup>
import { getGoogleLoginButtonUrl } from './getGoogleLoginButtonUrl.js'

const from = '/login'
</script>

<template>
<a :href="getGoogleLoginButtonUrl(from)" class="google-login-button">
  <img src="https://developers.google.com/static/identity/images/g-logo.png" alt="Google Logo" />
  <span>Google Sign In</span>
</a>
</template>

<style>
.google-login-button {
  color: #fff;
  background: #07f;
  padding: 10px 20px;
  border-radius: 6px;
}
</style>
