import type { Roles } from '@/enum/Roles'

export interface Registration {
  username: string
  password: string
  confirmPassword: string
  telegramLogin: string
  championPartnersLogin: string
  roles?: Roles[]
  bonus?: number
}
