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
    // Backend get userinfo send with bearer header with access_token to:
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
