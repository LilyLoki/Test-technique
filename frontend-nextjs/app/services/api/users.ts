export const BASE_URL = 'http://localhost:8000'

export default async function fetchMe() {
  return fetch(`${BASE_URL}/api/me`).then((response) => {
    if (response.status === 200) {
      console.log('User authenticated')
      return response.json()
    } else {
      console.log('User not authenticated')
      return null
    }
  })
}
