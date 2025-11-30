'use client'

import { useState } from 'react'
import { login } from '@/app/services/api/users'
import { useRouter } from 'next/navigation'
import { useAuth } from '../contexts/AuthContext'

export default function LoginForm({}) {
  const { login } = useAuth()
  const [error, setError] = useState<string | null>(null)
  const router = useRouter()

  const handleSubmit = async (event: React.FormEvent<HTMLFormElement>) => {
    event.preventDefault()
    setError(null)

    const formData = new FormData(event.currentTarget)
    const username = formData.get('username')
    const password = formData.get('password')

    try {
      await login(username, password)
      router.push('/questionnaire/list')
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }

  return (
    <form
      onSubmit={handleSubmit}
      className="max-w-md mx-auto bg-white p-6 rounded shadow"
    >
      <h2 className="text-2xl text-black mb-4 font-bold text-center">
        Connexion
      </h2>
      <input
        type="text"
        placeholder="Username"
        name="username"
        className="w-full text-black mb-4 p-2 border border-gray-300 rounded"
      />
      <input
        type="password"
        name="password"
        placeholder="Password"
        className="w-full text-black mb-4 p-2 border border-gray-300 rounded"
      />
      <p className="text-red-500 mb-4 text-center">{error}</p>
      <button
        type="submit"
        className="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
      >
        Se connecter
      </button>
    </form>
  )
}
