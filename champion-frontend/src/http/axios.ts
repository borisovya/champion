import axios from 'axios';

const baseURL = import.meta.env.VITE_BASE_URL;
axios.defaults.baseURL = baseURL;
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

axios.get('sanctum/csrf-cookie')
  .catch((e) => console.log('Failed to fetch CSRF token:', e));

axios.interceptors.request.use((config) => {
  function getCookie(cookieName: string): string | undefined {
    const cookiePairs = document.cookie.split(';');
    for (const pair of cookiePairs) {
      const [key, value] = pair.split('=');
      if (key.trim() === cookieName) {
        return value.split('%')[0].trim();
      }
    }
    return undefined;
  }

  config.headers['X-XSRF-TOKEN'] = getCookie('XSRF-TOKEN');

  return config;
}, (error) => {
  return Promise.reject(error);
});

axios.interceptors.response.use((response) => {

  return response;
}, (error) => {

  if (error.response?.status === 401 || error.response?.status === 419) {
    return false;
  }

  return Promise.reject(error);
});

export default axios;