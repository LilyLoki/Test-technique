'use client'

export default function LoginForm({}) {
  
  return (
    <form
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
      <button
        type="submit"
        className="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
      >
        Se connecter
      </button>
    </form>
  )
}
