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
    setLoading(true)
    fetchChoiceByUrl(urlChoice)
      .then((data) => {
        setChoice(data)
      })
      .catch(() => {
        setChoice(null)
      })
      .finally(() => {
        setLoading(false)
      })
  }, [urlChoice])

  function handleClick() {
    if (!choice) return
    const next = choice.nextQuestion
    console.log("NEXT =", next)
    if (typeof next === "string" && next.trim().length > 0) {
    onNext(next)
  } else {
    console.log("FINI → onEnd()")
    onEnd()
  }
  }

  return (
    <li className="bg-grey shadow-md rounded-lg p-6 hover:shadow-xl m-4 border-2">
      <button onClick={handleClick} className="text-xl font-semibold text-gray-900 text-left mb-2">
        {loading ? 'Loading…' : choice?.choiceText ?? 'No text'}
      </button>
    </li>
  )
}
