import type {Product} from '@/types/Products';

export interface Order {
  id: number
  orderNumber: string | number
  items: Product[]
  totalPrice: number
  status: boolean
  createdAt: string
  username: string
  championPartnersLogin: string
}
