export const BASE_URL = 'http://localhost:8000'

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
