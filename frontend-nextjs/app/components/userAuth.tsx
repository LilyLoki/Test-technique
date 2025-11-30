'use client'

import Link from 'next/link'
import { useAuth } from '../contexts/AuthContext'

export default function UserAuth({}) {
  const { user, isLoading, logout } = useAuth()

  if (isLoading) {
    return <div>Chargement...</div>
  }

  if (user) {
    return (
      <div className="flex flex-row items-center gap-4 p-4">
        <p className="text-center font-bold text-xl text-800">
          {user.username}
        </p>
        <button
          onClick={logout}
          className="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 p-2 rounded inline-block"
        >
          Logout
        </button>
      </div>
    )
  } else {
    return (
      <div className="flex flex-row items-center gap-4 p-4">
        <Link
          href="/login"
          className="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 p-2 rounded inline-block"
        >
          Login
        </Link>
      </div>
    )
  }
}
