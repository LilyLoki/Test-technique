'use client'

import React, { useEffect, useState } from 'react'
import ChoiceItem from './choiceItem'
import { fetchQuestionByUrl } from '../services/api/questionnaires'
import { Question } from '../types/questionType'
import MediaItem from './mediaItem'

type Props = {
  urlQuestion: string
}

export default function QuestionItem({ urlQuestion }: Props) {
  const [question, setQuestion] = useState<Question | null>(null)
  const [loading, setLoading] = useState(true)
  const [finished, setFinished] = useState(false)

  useEffect(() => {
    const loadData = async () => {
      setLoading(true)
      const data = await fetchQuestionByUrl(urlQuestion)
      setQuestion(data)
      setLoading(false)
    }

    loadData()
  }, [urlQuestion])

  function handleNext(nextUrl: string) {
    setLoading(true)
    fetchQuestionByUrl(nextUrl)
      .then((data) => setQuestion(data))
      .finally(() => setLoading(false))
  }

  function handleEnd() {
    setFinished(true)
  }

  if (loading) {
    return <div className="p-4">Chargement de la question...</div>
  }
  if (finished) {
    return (
      <div className="bg-white shadow-md rounded-lg p-6 hover:shadow-xl">
        <h2 className="text-xl font-semibold text-gray-900 text-center mb-2">
          Questionnaire Terminé !
        </h2>
      </div>
    )
  }

  const choices = question?.choices ?? []

  return (
    <div className="bg-white shadow-md rounded-lg p-6 hover:shadow-xl">
      <h2 className="text-xl font-semibold text-gray-900 mb-2">
        {question?.questionText ?? 'No question text'}
      </h2>

      <MediaItem
        mediaType={question?.mediaType || 'text'}
        mediaUrl={question?.mediaUrl || ''}
      />

      <ul>
        {choices.map((urlChoice: string) => (
          <ChoiceItem
            key={urlChoice}
            urlChoice={urlChoice}
            onNext={handleNext}
            onEnd={handleEnd}
          />
        ))}
      </ul>
    </div>
  )
}
