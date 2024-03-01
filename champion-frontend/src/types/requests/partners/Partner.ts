import type { Roles } from '@/enum/Roles'

export interface PartnerCreate {
  name: string
  email: string
  telegram: string
  championId: number
  championLogin: string
  bonusBalance: number
}

export interface PartnerUpdate {
  id: number
  username: string
  telegramLogin: string
  championPartnersLogin: string
  balance?: number
  roles: Roles[]
  userIdentifier: string
}
