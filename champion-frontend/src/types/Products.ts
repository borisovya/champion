import type { Category } from '@/types/Category'

export interface Product {
  id: number
  name: string
  description: string
  price: number
  status: boolean
  category: Category
  image: string
}
