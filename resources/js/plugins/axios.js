import axios from 'axios';

const instance = axios.create({
  baseURL: import.meta.env.VITE_APP_URL + '/api' || 'http://localhost:8000/api',
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

function refreshAccessToken() {
  const refreshToken = localStorage.getItem('refreshToken');
  instance.put('/session', { refresh_token: refreshToken })
    .then(response => {
      console.log('Token refreshed:', response.data);
      const { access_token } = response.data;
      localStorage.setItem('accessToken', access_token);
    })
    .catch(error => {
      console.error('Error refreshing token:', error);
      // Menghapus token jika gagal memperbarui token
      localStorage.removeItem('accessToken')
      localStorage.removeItem('refreshToken')

      // Redirect ke halaman login
      window.location = '/login'
    })
}

// interceptors
const currentPath = window.location.pathname;
instance.interceptors.request.use(config => {
  const token = localStorage.getItem('accessToken');
  if (token && currentPath !== '/login') {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

instance.interceptors.response.use(
  response => response,
  error => {
    const responseData = error?.response?.data || {};
    if (error.response.status === 401) {
      if (responseData?.err === 'ERR_INVALID_ACCESS_TOKEN') {
        refreshAccessToken();
      }
    }

    return Promise.reject(error)
  }
)

export default instance;
