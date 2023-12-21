import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    isAuthenticated: false,
    token: '',
    user: {
      id: '',
      name: '',
      email: '',
    },
  }),
  getters: {
    getIsAuthenticated: (state) => state.isAuthenticated,
    getToken: (state) => state.token,
    getUser: (state) => state.user,
    getUserId: (state) => state.user.id,
    getUserName: (state) => state.user.name,
  },
  persist: true,
})
