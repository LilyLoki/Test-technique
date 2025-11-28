export const BASE_URL =
  typeof window === 'undefined'
    ? process.env.NEXT_SERVER_BASE_URL || 'http://backend:8000' // server-side (SSR)
    : process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:8000' // client-side

export default async function fetchAllQuestionnaires() {
  return fetch(`${BASE_URL}/api/questionnaires`).then((response) => {
    return response.json()
  })
}

export async function fetchQuestionnaireById(id: string) {
  return fetch(`${BASE_URL}/api/questionnaires/${id}`).then((response) => {
    return response.json()
  })
}

export async function fetchQuestionByUrl(urlQuestion: string) {
  return fetch(`${BASE_URL}${urlQuestion}`).then((response) => {
    return response.json()
  })
}

export async function fetchChoiceByUrl(urlChoice: string) {
  return fetch(`${BASE_URL}${urlChoice}`).then((response) => {
    return response.json()
  })
}
