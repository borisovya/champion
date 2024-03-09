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

export const signOut = async (request: { token: string }): Promise<boolean> => {
  try {
    const res = await axios.post('v1/auth/sign-out', request)
    return res.status === 200
  } catch (e) {
    return false
  }
}

export const resetPassword = async (request: { email: string }) => {
  await axios.post('v1/reset-password', { ...request })
}

export const refreshAuth = async (): Promise<number | string> => {
  const res = await axios.get('v1/auth/refresh')

  if (res.status === 200) {
    return res.data.token
  } else {
    return res.status
  }
}
