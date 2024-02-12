import axios from '../axios.ts';
import {GeneralResponse} from '../../types/GeneralResponse.ts';
import {TransactionsHistory} from '../../types/responses/clientAccounts/TransactionsHistoryResponse.ts';
import type {PartnerCreate, PartnerUpdate} from '@/types/requests/partners/Partner';
import type {Partner} from '@/types/Partner';

export const getPartnerList = async (): Promise<any | null> => {
  try {
    const res = await axios.get<Partner[]>(`web-api/v1/partners`)

    return res ? res.data : null;
  }
  catch (e) {
    return null;
  }
};

export const getPartner = async (request: {id: number | string}): Promise<GeneralResponse<TransactionsHistory[]> | null> => {
  try {
    const res = await axios.get<Partner>(`web-api/v1/partner/${request.id}`)

    return res ? res.data : null;
  }
  catch (e) {
    return null;
  }
};

export const updatePartner = async (partnerId: string | number, request: PartnerUpdate) => {
  try {
    const response = await axios.post<Partner>('web-api/v1/partner/update/' + partnerId, request)

    return response.data
  }
  catch (e) {
    throw e
  }
}

export const createPartner= async (request: PartnerCreate) => {
  try {
    const res = await axios.post('web-api/v1/partner/create', request)

    return res.data
  }
  catch (e) {
    throw e
  }
}