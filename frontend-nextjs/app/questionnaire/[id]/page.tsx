import { fetchQuestionnaireById } from '@/app/services/api/questionnaires'
import { Questionnaire } from '../../types/questionnaireType'
import QuestionItem from '../../components/questionItem'
import Link from 'next/link'

export const dynamic = 'force-dynamic'

export default async function QuestionnairePage({
  params,
}: {
  params: Promise<{ id: string }>
}) {
  const { id } = await params
  const questionnaire: Questionnaire = await fetchQuestionnaireById(id)
  const questions = questionnaire.questions

  return (
    <div className="max-w-4xl mx-auto px-4 py-8 flex flex-col justify-center">
      <h1 className="text-xl font-semibold text-white-900 mb-2">
        {questionnaire.title}
      </h1>
      <p className="text-white-700 mb-4">{questionnaire.description}</p>
      <QuestionItem urlQuestion={questions[0]} />
      <Link
        href="/questionnaire/list"
        className="bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-2 px-4 rounded-lg shadow-md m-4 inline-block"
      >
        Retour à la liste de Questionnaires
      </Link>
    </div>
  )
}
