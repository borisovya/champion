import axios from '../axios.ts'
import type { PartnerUpdate } from '@/types/requests/partners/Partner'
import type { Partner } from '@/types/Partner'
import { isAxiosError } from 'axios'
export const getPartnerList = async (): Promise<Partner[] | []> => {
  try {
    const res = await axios.get<Partner[]>(`v1/user`)

    return res ? res.data : []
  } catch (e) {
    return []
  }
}

export const getPartner = async (id: number | string): Promise<Partner | string | null> => {
  try {
    const res = await axios.get<Partner>(`v1/user/${id}`)

    return res && res.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}

export const updatePartner = async (request: PartnerUpdate): Promise<Partner | string | null> => {
  try {
    const response = await axios.patch<Partner>(`v1/user/${request.id}`, request)

    return response.data
  } catch (e) {
    if (isAxiosError(e)) {
      return e.response.data.message
    }
    return null
  }
}
