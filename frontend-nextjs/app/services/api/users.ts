import { responseCookiesToRequestCookies } from 'next/dist/server/web/spec-extension/adapters/request-cookies'

export const BASE_URL = 'http://localhost:8000'

export default async function fetchMe() {
  const token = localStorage.getItem('authToken')
  if (!token) {
    return null
  }
  return fetch(`${BASE_URL}/api/me`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  }).then((response) => {
    if (response.status === 200) {
      return response.json()
    } else {
      return null
    }
  })
}

export async function login(
  username: FormDataEntryValue | null,
  password: FormDataEntryValue | null,
) {
  if (
    !username ||
    !password ||
    typeof username !== 'string' ||
    typeof password !== 'string'
  ) {
    throw new Error("nom d'utilisateur et/ou mot de passe vide")
  }
  return fetch(`${BASE_URL}/auth`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ username, password }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error('Échec de la connexion')
      }
      return response.json()
    })

    .then((data) => {
      localStorage.setItem('authToken', data.token)
      return data.token
    })

    .catch((error) => {
      console.error(error)
      throw error
    })
}

export function logout() {
  localStorage.removeItem('authToken')
}
