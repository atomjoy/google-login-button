<script setup>
// Sample callback page (!!! do it on backend !!!)

import { onMounted, ref, reactive } from 'vue'
import { useRoute, useRouter, RouterLink } from 'vue-router'
import axios from 'axios'

let user = ref(null)
let query = ref(null)
let token = ref(null)

// Get useinfo on server side
async function getUser(access_token) {
	if (access_token) {
		try {
			// Request (sample only get on server side)
			axios.defaults.headers.common = { Authorization: `Bearer ${access_token}` }
			const data = await axios.get(`https://www.googleapis.com/oauth2/v2/userinfo`)
			console.log('Api data', data)
			return data

			/*
			// Or with options
			const options = { headers: { Authorization: `Bearer ${access_token}` }}
			const data = await axios.get(`https://www.googleapis.com/oauth2/v2/userinfo`, options)
			const data = await axios.get(`https://www.googleapis.com/oauth2/v2/userinfo?alt=json&access_token=${access_token}`, options)
      */
		} catch (err) {
			console.log('Api error', err)
			throw Error(err)
		}
	}
}

onMounted(async () => {
	// Route
	const route = useRoute()
	// Url query params
	query.value = route?.hash
	if (query.value) {
		// Cut access_token
		token.value = query.value.replace('#', '').split('=')[2].replace('&token_type', '')
		// Server userinfo
		user.value = await getUser(token.value)
		// Console
		console.log('Query Params', query.value)
		console.log('Access Token', token.value)
		console.log('User Info', user.value)
	}
})
</script>

<template>
	<h2>Google backend page example with js</h2>
	<p>Api access_token: {{ token }}</p>
	<p>Api access_token: {{ user?.data }}</p>
</template>
