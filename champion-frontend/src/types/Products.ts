import type {Category} from '@/types/Category';

export interface Product {
  id: number,
  title: string,
  description: string,
  price: number,
  status: boolean,
  category: Category,
  image: string
}
