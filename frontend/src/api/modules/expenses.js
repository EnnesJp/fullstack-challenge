import Request from 'src/api/Request'

export default function expensesService() {
  const { get, post, update, remove } = Request()
  const _apiUrl = 'api/expense/'

  const getExpenses = async (expenseBody) => {
    try {
      const response = await get(_apiUrl, true, expenseBody)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  const removeExpense = async (expenseId) => {
    try {
      const response = await remove(`${_apiUrl}${expenseId}`, true)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  const createExpense = async (expenseBody) => {
    try {
      const response = await post(_apiUrl, expenseBody, true)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  const updateExpense = async (expenseId, expenseBody) => {
    try {
      const response = await update(`${_apiUrl}${expenseId}`, expenseBody, true)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  const getExpense = async (expenseId) => {
    try {
      const response = await get(`${_apiUrl}${expenseId}`, true)
      return response
    } catch (error) {
      console.error(error)
    }
  }

  return {
    getExpenses,
    removeExpense,
    createExpense,
    updateExpense,
    getExpense,
  }
}
