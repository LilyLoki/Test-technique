export const BASE_URL = "http://localhost:8000/api";

export default async function fetchAllQuestionnaires() {
    return fetch(`${BASE_URL}/questionnaires`).then((response) => {
        return response.json();
    });
}