import Request from 'src/api/Request'

export default function usersService() {
  const { post } = Request()
  const _apiUrl = 'api/'

  const createUser = async (userBody) => {
    try {
      const response = await post(`${_apiUrl}register`, userBody, false)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  return {
    createUser,
  }
}
