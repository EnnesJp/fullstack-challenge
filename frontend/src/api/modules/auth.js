import { api } from 'boot/axios'
import Request from 'src/api/Request'
import { useAuthStore } from 'stores/auth'

export default function authService() {
  const { post } = Request()
  const _apiUrl = 'api/'
  const store = useAuthStore()

  const login = async (credentials) => {
    try {
      const response = await post(`${_apiUrl}login`, credentials, false)
      store.$patch((state) => {
        ;(state.isAuthenticated = true),
          (state.token = response.content.data.token),
          (state.user = response.content.data.user)
      })
      return response
    } catch (error) {
      console.error(error)
    }
  }

  const logout = async () => {
    try {
      api.defaults.headers.common.Authorization = ''
      store.$patch((state) => {
        ;(state.isAuthenticated = false), (state.token = ''), (state.user = {})
      })

      return response
    } catch (error) {
      console.error(error)
    }
  }

  return {
    login,
    logout,
  }
}
