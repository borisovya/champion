import { defineStore } from "pinia";
import type {User} from '@/types/User';


export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
  }),
  actions: {
    setUser(user: User) {
      this.user = user
    },
    getUser(): User | null {
      return this.user
    },
    removeUser() {
      return this.user = null;
    }
  },
});