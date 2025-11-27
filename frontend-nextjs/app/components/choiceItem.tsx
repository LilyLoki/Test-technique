import { fetchChoiceByUrl } from '../services/api/questionnaires'
export default async function ChoiceItem({ urlChoice }: { urlChoice: string }) {
  const choice = await fetchChoiceByUrl(urlChoice)

  return (
    <li className="bg-grey shadow-md rounded-lg p-6 hover:shadow-xl m-4 border-2">
      <button className="text-xl font-semibold text-gray-900 mb-2">
        {choice.choiceText}
      </button>
    </li>
  )
}