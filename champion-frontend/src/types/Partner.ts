import { Roles } from '@/enum/Roles'

export interface Partner {
  id: number
  username: string
  telegramLogin: string
  championPartnersLogin: string
  roles: Roles[]
  userIdentifier: string
  balance: number
}
