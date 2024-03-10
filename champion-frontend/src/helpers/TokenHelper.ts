import { deleteFromCookie, getFromCookie, setCookie } from '@/helpers/CookieHelper'
import type { User } from '@/types/User'
import { getNetAccessToken } from '@/http/auth/AuthServices'
import { useUserStore } from '@/store/useStore'

export const getAccessToken = () => {
  return getFromCookie(import.meta.env.VITE_ACCESS_TOKEN_COOKIE)
}

export const getUserFromToken = (): User | null => {
  const token = getAccessToken()

  if (!token) {
    return null
  }

  return JSON.parse(atob(token.split('.')[1]))
}

export const refreshToken = async () => {
  const token = await getNetAccessToken()

  if (!token) {
    throw new Error('Access denied')
  }

  setCookie(import.meta.env.VITE_ACCESS_TOKEN_COOKIE, token)
}

export const flushTokenize = () => {
  useUserStore().setUser(null)
  deleteFromCookie(import.meta.env.VITE_ACCESS_TOKEN_COOKIE)
}
