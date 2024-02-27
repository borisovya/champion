import type { Partner } from '@/types/Partner'
import axios from 'axios'
import { Category } from '@/types/Category'
import type { Error } from '@/types/Error'

export const getCategories = async (): Promise<Category[] | []> => {
  try {
    const res = await axios.get(`v1/category`)

    return res ? res.data : []
  } catch (e) {
    return []
  }
}

export const addCategories = async (request: {
  name: string
  status: 1 | 0
}): Promise<any | null> => {
  try {
    const res = await axios.post(`v1/category`, request)

    return res ? res.data : null
  } catch (e) {
    return null
  }
}

export const toggleCategoryStatus = async (
  id: number | string
): Promise<Category | Error | null> => {
  try {
    const res = await axios.patch<Category | Error>(`v1/category/toggle-status/${id}`)
    return res.data ?? res
  } catch (e) {
    return null
  }
}

export const deleteCategory = async (id: number | string): Promise<number> => {
  try {
    const res = await axios.delete(`v1/category/${id}`)

    return res.status
  } catch (e) {
    return e.status
  }
}
