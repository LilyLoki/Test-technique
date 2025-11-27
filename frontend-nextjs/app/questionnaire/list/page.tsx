import fetchAllQuestionnaires from '../../services/api/questionnaires';
import { Questionnaire } from '../../types/questionnaireType';
import QuestionnaireItem from '../../components/questionnaireItem';

export default async function QuestionnaireListPage() {
    const data = await fetchAllQuestionnaires();
    const questionnaires: Questionnaire[] = data.member

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-center mb-8 text-white-800" >
        Liste de Questionnaires
      </h1>
      <ul className="space-y-6">
        { questionnaires.map((questionnaire) => (
            <QuestionnaireItem 
              key={questionnaire.id}
              questionnaire={questionnaire}
            />
          ))
        }
      </ul>
    </div>
  );
}
