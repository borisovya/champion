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
  name: string
  email: string
  telegram: string
  championId: number
  championLogin: string
  bonusBalance: number
}
