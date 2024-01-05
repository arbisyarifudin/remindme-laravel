import axios from 'axios'

const instance = axios.create({
  baseURL: import.meta.env.VITE_APP_URL + '/api' || 'http://localhost:9001/api',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    Accept: 'application/json'
  }
})

let isRefreshing = false

function refreshAccessToken () {
  if (isRefreshing) {
    return
  }

  const refreshToken = localStorage.getItem('refreshToken')

  if (!refreshToken) {
    // redirect to login page
    window.location = '/login'
    return
  }

  isRefreshing = true

  instance.put('/session', { refresh_token: refreshToken })
    .then(response => {
      console.log('Token refreshed:', response.data)
      const { access_token: accessToken, refresh_token: refreshToken } = response.data?.data || {}
      localStorage.setItem('accessToken', accessToken)
      localStorage.setItem('refreshToken', refreshToken)

      // reload page
      window.location.reload()
    })
    .catch(error => {
      console.error('Error refreshing token:', error)

      // delete token
      localStorage.removeItem('accessToken')
      // localStorage.removeItem('refreshToken')
      localStorage.removeItem('user')

      // redirect to login page
      window.location = '/login'
    }).finally(() => {
      isRefreshing = false
    })
}

// interceptors
const currentPath = window.location.pathname
instance.interceptors.request.use(config => {
  const url = config.url
  const method = config.method

  // if request is to refresh token, then add refresh token to header
  if (url === '/session' && method === 'put') {
    const token = localStorage.getItem('refreshToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
  } else {
    // if request is not to refresh token, then add access token to header
    const token = localStorage.getItem('accessToken')
    if (token && currentPath !== '/login') {
      config.headers.Authorization = `Bearer ${token}`
    }
  }

  return config
}, error => {
  return Promise.reject(error)
})

instance.interceptors.response.use(
  response => response,
  error => {
    const responseData = error?.response?.data || {}
    if (error.response.status === 401) {
      // if error is ERR_INVALID_ACCESS_TOKEN and isRefreshing is false, then refresh token
      if (responseData?.err === 'ERR_INVALID_ACCESS_TOKEN' && !isRefreshing) {
        refreshAccessToken()
      } else {
        // delete token
        localStorage.removeItem('accessToken')
        localStorage.removeItem('refreshToken')
        localStorage.removeItem('user')

        // redirect to login page
        window.location = '/login'
      }
    }

    return Promise.reject(error)
  }
)

export default instance
