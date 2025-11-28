import Link from "next/link";

import { Questionnaire } from '@/app/types/questionnaireType'
export default async function QuestionnaireItem({
  questionnaire,
}: {
  questionnaire: Questionnaire
}) {
  return (
    <div>
      <li
        key={questionnaire.id}
        className="bg-white shadow-md rounded-lg p-6 hover:shadow-xl"
      >
      <Link href={`/questionnaire/${questionnaire.id}`}>
          <h2 className="text-xl font-semibold text-gray-900 mb-2">
            {questionnaire.title}
          </h2>
          <p className="text-gray-700">{questionnaire.description}</p>
          <p className="text-sm text-gray-500 mt-2">
            Créé le: {new Date(questionnaire.creationDate).toLocaleDateString()}
          </p>
        </Link>
      </li>
    </div>
  )
}
