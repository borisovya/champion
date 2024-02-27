import type {Partner} from '@/types/Partner';
import axios from 'axios';

export const getCategories = async (): Promise<any | null> => {
  try {
    const res = await axios.get(`v1/category`)

    return res ? res.data : null
  } catch (e) {
    return null
  }
}

export const addCategories = async (request: {name: string, active: boolean}): Promise<any | null> => {
  try {
    const res = await axios.post(`v1/category`, request)

    return res ? res.data : null
  } catch (e) {
    return null
  }
}

export const toggleCategoryStatus = async (): Promise<any | null> => {
  try {
    const res = await axios.patch(`v1/category`)

    return res ? res.data : null
  } catch (e) {
    return null
  }
}

export const deleteCategory = async (id: number | string): Promise<any | null> => {
  try {
    const res = await axios.delete(`v1/category/${id}`)

    return res ? res.data : null
  } catch (e) {
    return null
  }
}

