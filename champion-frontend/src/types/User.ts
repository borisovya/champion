import type {Roles} from '@/enum/Roles';

export interface User {
  championPartnersLogin: string
  exp: number
  iat: number
  roles: Roles[]
  telegramLogin: string
  username: string
}
