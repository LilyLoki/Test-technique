'use client'
import fetchMe from '../services/api/users'
import Link from 'next/link'

export default async function UserAuth({}) {
  const user = await fetchMe().then((data) => data)
  if (user) {
    return (
      <div>
        <p>Welcome, {user.username}!</p>
      </div>
    )
  } else {
    return (
      <Link
        href="/login"
        className="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 p-2 rounded inline-block"
      >
        Login
      </Link>
    )
  }
}
