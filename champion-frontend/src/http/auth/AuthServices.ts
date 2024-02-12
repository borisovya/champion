import axios from '../axios.ts';
import type {Registration} from '@/types/requests/auth/Auth';

export const login = async (login: string, password: string): Promise<boolean> => {
  try {

    return await axios.post('web-api/v1/login', {login, password});
  }
  catch (e) {
    return false;
  }
};
export const register = async (request: Registration): Promise<boolean> => {
  try {
    return await axios.post('web-api/v1/register', {...request});
  }
  catch (e) {
    return false;
  }
};

export const logout = async (): Promise<boolean> => {
  try {
    await axios.post('web-api/v1/logout');
    return true
  }
  catch (e) {
    return false;
  }
};

export const getUserData = async (): Promise<any> => {
  try {
    const res = await axios.get('web-api/v1/profile')
    return res.data ? res.data : null;
  }
  catch (e) {
    return null;
  }
};

export const resetPassword = async (request: { email: string }) => {
  try {
    await axios.post('web-api/v1/reset-password', {...request})
  }
  catch (e) {
    throw e
  }
}

