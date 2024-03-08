import axios from 'axios'
import { deleteFromCookie, getFromCookie } from '@/helpers/CookieHelper'

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
  (error) => {
    if (error.response?.status === 401) {
      deleteFromCookie('token')
    }

    return Promise.reject(error)
  }
)

export default axios
