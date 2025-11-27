import ChoiceItem from './choiceItem'
import { fetchQuestionByUrl } from '../services/api/questionnaires'
export default async function QuestionItem({
  urlQuestion,
}: {
  urlQuestion: string
}) {
  const question = await fetchQuestionByUrl(urlQuestion)
  const choices = question.choices

  return (
    <div className="bg-white shadow-md rounded-lg p-6 hover:shadow-xl">
      <h2 className="text-xl font-semibold text-gray-900 mb-2">
        {question.questionText}
      </h2>
      <ul>
        {choices.map((urlChoice: string) => (
          <ChoiceItem 
          key={urlChoice}
          urlChoice={urlChoice} />
        ))}
      </ul>
    </div>
  )
}
