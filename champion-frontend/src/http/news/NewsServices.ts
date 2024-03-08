import { isAxiosError } from 'axios'
import axios from '@/http/axios'
import type { News } from '@/types/News'
import type { CreateNewsRequest } from '@/types/requests/news/News'

export const getNewsList = async (): Promise<News[] | []> => {
  try {
    const res = await axios.get<News[]>('v1/post')

    return res ? res.data : []
  } catch (e) {
    return []
  }
}

export const getNews = async (id: number | string): Promise<News | string | null> => {
  try {
    const res = await axios.get<News>(`v1/post/${id}`)

    return res && res.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const updateNews = async (
  id: number | string,
  request: CreateNewsRequest
): Promise<News | string | null> => {
  try {
    const response = await axios.put<News>(`v1/post/${id}`, request)
    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const createNews = async (request: CreateNewsRequest): Promise<News | string | null> => {
  try {
    const response = await axios.post<News>(`v1/post`, request)

    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}
export const deleteNews = async (postId: number | string): Promise<number> => {
  try {
    const response = await axios.delete(`v1/post/${postId}`)

    return response.status
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.status
    }
    return null
  }
}

export const newsBindImage = async (postId: number | string, file: File): Promise<News> => {
  try {
    const response = await axios.post<News>(
      `v1/post/bind-image/${postId}`,
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
