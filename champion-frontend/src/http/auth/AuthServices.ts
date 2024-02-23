import axios from '@/http/axios.ts'
import type {Registration} from '@/types/requests/auth/Auth'

export const signIn = async (credentials: { username: string, password: string }): Promise<boolean> => {
  try {
    return await axios.post('api/v1/auth/sign-in', credentials)
  } catch (e) {
    return false
  }
}
export const signUp = async (request: Registration): Promise<boolean> => {
  try {
    return await axios.post('v1/auth/sign-up', {...request})
  } catch (e) {
    return false
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
    await axios.post('web-api/v1/reset-password', {...request})
  } catch (e) {
    throw e
  }
}
