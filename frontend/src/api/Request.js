import { api } from 'boot/axios'

export default function Request() {
  function getToken() {
    return JSON.parse(window.localStorage.auth).token
  }

  function hasUserToken() {
    return !!getToken()
  }

  function getStrBearerToken() {
    return `Bearer ${getToken()}`
  }

  const getHeaders = (bNeedsAuth) => {
    api.defaults.headers.common['Content-Type'] = 'application/json'
    if (bNeedsAuth && hasUserToken()) {
      api.defaults.headers.common.Authorization = getStrBearerToken()
    }
  }

  const get = async (url, bNeedsAuth, data) => {
    try {
      getHeaders(bNeedsAuth)
      const response = await api.get(url, {
        params: data,
      })
      return response.data
    } catch (error) {
      const getReturn = {
        error: error.response.data,
        status: error.response.status,
      }

      return getReturn
    }
  }

  const post = async (url, data, bNeedsAuth) => {
    try {
      getHeaders(bNeedsAuth)
      const response = await api.post(url, data)
      return response.data
    } catch (error) {
      const postReturn = {
        error: error.response.data,
        status: error.response.status,
      }

      return postReturn
    }
  }

  const update = async (url, data, bNeedsAuth) => {
    try {
      getHeaders(bNeedsAuth)
      const response = await api.put(url, data)
      return response.data
    } catch (error) {
      const updateReturn = {
        error: error.response.data,
        status: error.response.status,
      }

      return updateReturn
    }
  }

  const remove = async (url, bNeedsAuth) => {
    try {
      getHeaders(bNeedsAuth)
      const response = await api.delete(url)
      return response.data
    } catch (error) {
      const removeReturn = {
        error: error.response.data,
        status: error.response.status,
      }

      return removeReturn
    }
  }

  return {
    get,
    post,
    update,
    remove,
  }
}
