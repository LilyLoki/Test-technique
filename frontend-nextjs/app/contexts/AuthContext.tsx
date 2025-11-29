'use client'

import { createContext, useContext, useState, useEffect } from 'react'
import fetchMe from '@/app/services/api/users'
import { login, logout } from '@/app/services/api/users'
import { useRouter } from 'next/navigation'
import { User } from '@/app/types/userType'

interface AuthContextType {
  user: User | null
  isLoading: boolean
  login: (
    username: FormDataEntryValue | null,
    password: FormDataEntryValue | null,
  ) => Promise<void>
  logout: () => void
}

const AuthContext = createContext<AuthContextType | null>(null)

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [user, setUser] = useState<User | null>(null)
  const [isLoading, setIsLoading] = useState(true)
  const router = useRouter()

  const loadUser = async () => {
    try {
      setIsLoading(true)
      const userData = await fetchMe()
      setUser(userData)
    } catch (error) {
      console.error(
        "Erreur lors du chargement initial de l'utilisateur:",
        error,
      )
      setUser(null)
    } finally {
      setIsLoading(false)
    }
  }

  useEffect(() => {
    loadUser()
  }, [])

  const loginContext = async (
    username: FormDataEntryValue | null,
    password: FormDataEntryValue | null,
  ): Promise<void> => {
    try {
      await login(username, password)
      await loadUser()
      router.push('/questionnaire/list')
    } catch (error) {
      throw error
    }
  }

  const logoutContext = async () => {
    try {
      logout()
      setUser(null)
      router.push('/login')
    } catch (error) {
      throw error
    }
  }

  return (
    <AuthContext.Provider
      value={{
        user,
        isLoading,
        login: loginContext,
        logout: logoutContext,
      }}
    >
      {children}
    </AuthContext.Provider>
  )
}

export const useAuth = () => {
  const context = useContext(AuthContext)
  if (context === null) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}
