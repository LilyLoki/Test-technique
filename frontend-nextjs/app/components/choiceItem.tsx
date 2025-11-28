'use client'

import React, { useEffect, useState } from 'react'
import { fetchChoiceByUrl } from '../services/api/questionnaires'
import { Choice } from '../types/choiceType'

type Props = {
  urlChoice: string
  onNext: (nextUrl: string) => void
  onEnd: () => void
}

export default function ChoiceItem({ urlChoice, onNext, onEnd }: Props) {
  const [choice, setChoice] = useState<Choice | null>(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const loadData = async () => {
      setLoading(true)
      const data = await fetchChoiceByUrl(urlChoice)
      setChoice(data)
      setLoading(false)
    }

    loadData()
  }, [urlChoice])

  function handleClick() {
    if (!choice) return
    const next = choice.nextQuestion
    console.log('NEXT =', next)
    if (typeof next === 'string' && next.trim().length > 0) {
      onNext(next)
    } else {
      console.log('FINI → onEnd()')
      onEnd()
    }
  }

  return (
    <li className="m-4 border-2 rounded-lg shadow-md hover:shadow-xl">
      <button
        onClick={handleClick}
        className="w-full block text-left px-6 py-4 text-xl font-semibold text-gray-900 rounded-lg"
      >
        {loading ? 'Loading…' : (choice?.choiceText ?? 'No text')}
      </button>
    </li>
  )
}
