import axios from 'axios'
import {deleteFromCookie, getFromCookie, setCookie} from '@/helpers/CookieHelper';
import {refreshAuth} from '@/http/auth/AuthServices';

axios.defaults.withCredentials = true

axios.defaults.baseURL = `/api`

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

axios.interceptors.request.use(
  (config) => {
    const token = getFromCookie('token')

    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }

    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

axios.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {

    if (error.response?.status === 401) {
      const res = await refreshAuth()
      if(isNumber(res) && res !== 200) {
        deleteFromCookie('token')
        deleteFromCookie('refresh_token')
      } else {
        setCookie('token', res)
      }
    }

    return Promise.reject(error)
  }
)

export default axios
