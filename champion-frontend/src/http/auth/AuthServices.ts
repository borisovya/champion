import axios from '@/http/axios.ts'
import type { Registration } from '@/types/requests/auth/Auth'
import { isAxiosError } from 'axios'

export const signIn = async (credentials: {
  username: string
  password: string
}): Promise<string | { message: string }> => {
  try {
    const res = await axios.post<{ token: string } | { message: string }>(
      'v1/auth/sign-in',
      credentials
    )

    if (!res) {
      return { message: 'Недействительные аутентификационные данные.' }
    }
    if (res.status === 200) {
      return res.data.token
    } else return res.data.message
  } catch (e) {
    if (isAxiosError(e)) {
      return { message: e.response.data.message }
    }
    return { message: 'Произошла ошибка сервера.' }
  }
}
export const signUp = async (request: Registration): Promise<string | { message: string }> => {
  try {
    const res = await axios.post<{ token: string } | { message: string }>('v1/auth/sign-up', {
      ...request
    })

    if (res.status === 200) {
      return res.data.token
    } else return res.data.message
  } catch (e) {
    if (isAxiosError(e)) {
      return { message: e.response.data.message }
    }
    return { message: 'Произошла ошибка сервера.' }
  }
}

export const pingPong = async (data?: any): Promise<boolean> => {
  try {
    return await axios.post('v1/auth/ping-pong', data)
  } catch (e) {
    return false
  }
}

export const signOut = async (): Promise<boolean> => {
  try {
    await axios.post('v1/logout')
    return true
  } catch (e) {
    return false
  }
}

export const getUserData = async (): Promise<any> => {
  try {
    const res = await axios.get('web-api/v1/profile')
    return res.data ? res.data : null
  } catch (e) {
    return null
  }
}

export const resetPassword = async (request: { email: string }) => {
  try {
    await axios.post('web-api/v1/reset-password', { ...request })
  } catch (e) {
    throw e
  }
}
