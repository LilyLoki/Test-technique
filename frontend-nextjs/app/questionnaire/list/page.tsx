import fetchAllQuestionnaires from '../../services/api/questionnaires';
import { Questionnaire } from '../../types/questionnaireType';

export default async function Page() {
    const $data = await fetchAllQuestionnaires();
    const $questionnaires: Questionnaire[] = $data.member

  return (
    <div>
      <h1>Questionnaire List Page</h1>
      <ul>
        { $questionnaires.map((questionnaire) => (
            <li key={questionnaire.id}>
                <h2>{questionnaire.title}</h2>
            </li>))
        }
      </ul>
    </div>
  );
}
