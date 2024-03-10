import axios from 'axios'
import { flushTokenize, refreshToken } from '@/helpers/TokenHelper'
import router from '@/router'

axios.defaults.withCredentials = true

axios.defaults.baseURL = `/api`

axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'

axios.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {
    const status = Number(error.response.status)

    if (/v\d+\/auth\/sign-in$/.test(error.config.url) && status === 401) {
      return new Promise((resolve, reject) => {
        reject(error)
      })
    }

    if (/v\d+\/auth\/refresh$/.test(error.config.url) && status === 401) {
      await router.push({
        name: 'login'
      })

      return new Promise((resolve, reject) => {
        reject(error)
      })
    }

    if (status !== 401 && status !== 403) {
      return new Promise((resolve, reject) => {
        reject(error)
      })
    }
    else {
      flushTokenize()
      await refreshToken()
    }

    return new Promise((resolve, reject) => {
      axios.request(error.config).then(response => {
        resolve(response)
      }).catch((error) => {
        reject(error)
      })
    })
  }
)

export default axios
