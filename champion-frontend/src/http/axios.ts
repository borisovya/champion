import axios from 'axios'
const baseURL = import.meta.env.VITE_BASE_URL;

axios.defaults.withCredentials = true

//axios.defaults.baseURL = `${baseURL}`
axios.defaults.baseURL = `/api`

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')

    if(token) {
      config.headers['Authorization'] =`Bearer ${token}`
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
    if (error.response?.status === 401 || error.response?.status === 419) {
      return false
    }

    return Promise.reject(error)
  }
)

export default axios
