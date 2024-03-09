import { isAxiosError } from 'axios'
import axios from '@/http/axios'
import type { Product } from '@/types/Products'
import type { CreateProductRequest } from '@/types/requests/shop/Product'
import { Error } from '@/types/Error'

export const getProductList = async (): Promise<Product[] | []> => {
  try {
    const res = await axios.get<Product[]>('v1/product')

    return res ? res.data : []
  } catch (e) {
    return []
  }
}

export const getProduct = async (id: number | string): Promise<Product | string | null> => {
  try {
    const res = await axios.get<Product>(`v1/product/${id}`)

    return res && res.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const updateProduct = async (
  id: number | string,
  request: CreateProductRequest
): Promise<Product | string | null> => {
  try {
    const response = await axios.put<Product>(`v1/product/${id}`, request)
    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const createProduct = async (
  request: CreateProductRequest
): Promise<Product | string | null> => {
  try {
    const response = await axios.post<Product>(`v1/product`, request)

    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const toggleProductStatus = async (id: number | string): Promise<Product | Error | null> => {
  try {
    const res = await axios.patch(`v1/product/toggle-status/${id}`)

    return res.data ?? res
  } catch (e) {
    return null
  }
}

export const deleteProduct = async (productId: number | string): Promise<number> => {
  try {
    const response = await axios.delete(`v1/product/${productId}`)

    return response.status
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.status
    }
    return null
  }
}

export const productBindImage = async (
  productId: number | string,
  file: File
): Promise<Product> => {
  try {
    const response = await axios.post<Product>(
      `v1/product/bind-image/${productId}`,
      { file },
      {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
    )

    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}
