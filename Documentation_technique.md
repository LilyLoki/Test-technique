# Documentation technique — Projet « Questionnaires interactifs »

# 1. Vue d'ensemble de l'implémentation actuelle

**Frontend**

* Next.js (Pages Router).
* Routes existantes côté client :

  * `/questionnaire/list` — liste des questionnaires
  * `/questionnaire/[id]` — interface de réponse
  * `/login` — formulaire d'authentification
* Communication : `fetch` vers l'API Symfony (API Platform).

**Backend**

* Symfony + API Platform exposant CRUD pour `Questionnaire`, `Question`, `Choice`.
* Endpoints complémentaires :

  * `POST /auth` — création du token JWT
  * `GET /api/me` — récupérer l'utilisateur connecté
  * `GET/PUT /api/users/{id}` — accès / modification d'un utilisateur
* Entités implémentées : `Questionnaire`, `Question`, `Choice`, `User`.
* Uploads médias : modélisés via `Question.mediaType` et `Question.mediaUrl` (URL de la ressource, stockage géré côté backend; implementation actuelle retourne des URL existantes — pas d'upload pour le moment).  
* Auth : JWT.  

**Base de données**

* PostgreSQL.
* Tables existantes : `questionnaire`, `question`, `choice`, `user`.
* Le schéma dbdiagram :

```text
Table questionnaire {
  questionnaireId int [pk, increment]
  title varchar
  description text
  creationDate datetime
}

Table question {
  questionId int [pk, increment]
  questionnaireId int [not null]
  questionText text
  mediaType varchar
  mediaUrl varchar
  isRoot bool
}

Table choice {
  choiceId int [pk, increment]
  questionId int [not null]
  choiceText text
  displayOrder int
  nextQuestionId int [ref: > question.questionId, default: null]
}

Table user {
  userId int [pk, increment]
  email varchar
  username varchar
  password varchar
  role varchar
}

Table questionnaire_session {
  sessionId int [pk, increment]
  questionnaireId int [not null]
  userId int [default: null]
  anonymousToken varchar
  startDate datetime
  endDate datetime
  status varchar // values: "not_started", "in_progress", "completed"
}

Table answer {
  id_answer int [pk, increment]
  sessionId int [not null]
  questionId int [not null]
  choiceId int [not null]
  timestamp datetime
}

Ref: question.questionnaireId > questionnaire.questionnaireId
Ref: choice.questionId > question.questionId
Ref: questionnaire_session.questionnaireId > questionnaire.questionnaireId
Ref: questionnaire_session.userId > user.userId
Ref: answer.sessionId > questionnaire_session.sessionId
Ref: answer.questionId > question.questionId
Ref: answer.choiceId > choice.choiceId

```

Les entités `questionnaire_session` et `answer` existent au niveau du MCD mais n'ont pas été implémentées en base côté projet.

**Docker / Infra**

* Services dans `docker-compose.yml` : `frontend` (Next.js), `backend` (Symfony), `db` (postgres), `adminer`.
* `docker compose up` démarre les services. La création des fixtures/BD nécessite un script Symfony (`composer db`) qui initialise la BD et insère des fixtures.

**Fonctionnalités implémentées**

* CRUD backend (via API Platform) pour `Questionnaire`, `Question`, `Choice`.
* Navigation dynamique dans l'arbre : la relation `Choice.nextQuestionId` est utilisée pour déterminer la prochaine question côté front et back.
* Authentification JWT et endpoints `auth` / `me`.

**Fonctionnalités manquantes / incomplètes**

* Interface d'administration pour créer/éditer/supprimer via UI (CRUD disponible uniquement via API / API Platform en mode auto-generated).
* Uploads de fichiers via UI admin (actuellement, `mediaUrl` doit pointer vers une URL déjà accessible).
* Reprise de questionnaire (sauvegarde de progression côté `questionnaire_session` + `answer`) non implémentée.

# 2. Diagramme d'architecture

```
FLUX 1 : Accès initial (Rendu Serveur / SSR)
Browser -- "1. HTTP GET http://localhost:3000" --> NextServer
NextServer -- "2. Fetch (SSR)<br>http://backend:8000" --> PHPServer
    
FLUX 2 : Interactions dynamiques (Rendu Client / CSR / Login)
Browser -.-> NextClient
NextClient -- "3. Fetch (CSR / AJAX)<br>http://localhost:8000" --> PHPServer

FLUX Backend vers Base de données
PHPServer -- "4. Doctrine (Port 5432)<br>host: db" --> Postgres
API_Platform --- PHPServer

Autres accès
Browser -- "Accès direct API<br>http://localhost:8000/api" --> PHPServer
Browser -- "Accès Adminer<br>http://localhost:7080" --> AdminerApp
AdminerApp -- "Connexion interne" --> Postgres
```

# 3. API — endpoints importants (résumé)

> Basé sur API Platform + routes supplémentaires fournies.

* `POST /auth` — reçoit `{email,password}` et retourne `{token}` (JWT).
* `GET /api/me` — données utilisateur (JWT required).
* `GET /api/questionnaires` — liste (API Platform standard)
* `GET /api/questionnaires/{id}` — détail d'un questionnaire
* `GET /api/questions/{id}` — détail question
* `GET /api/choices/{id}` — détail choice
* `POST/PUT/DELETE` pour `questionnaire`, `question`, `choice` (via API Platform, droits à configurer)

**Notes** :

* Les endpoints CRUD existent et sont fonctionnels ; l'UI admin n'a pas été développée mais on peut utiliser l'API Platform UI (Swagger / Hydra) pour manipuler les ressources.

# 4. ADR — décisions majeures

## ADR 001 — Base de données

**Contexte** : Fiabilité, relations, migrations.  
**Décision** : PostgreSQL.  
**Conséquences** : Bon support relationnel, transactions et types avancés. Le choix est implémenté.  
**Alternatives** : MariaDB/MySQL — possible, mais PostgreSQL choisi pour robustesse.  

## ADR 002 — Modélisation de l'arbre de décision

**Contexte** : Chaque question peut mener à une ou plusieurs questions enfants.  
**Décision** : Structure `Choice.nextQuestionId` (référence facultative vers `question.questionId`). `question.isRoot` pour marquer la racine.  
**Conséquences** : Arbre représenté par des relations directes. Permet une navigation simple : réponse -> choix -> nextQuestionId.  

## ADR 003 — Stockage des médias (photo/vidéo)

**Contexte** : Les questions peuvent avoir un média (photo/vidéo).  
**Décision** : Modèle simple `mediaType` + `mediaUrl` dans `Question`. Les URL pointent vers des ressources statiques exposées par le backend (ou CDN dans futur).  
**Conséquences** : Implémentation rapide (pas de gestion d'upload UI dans ce sprint).  

## ADR 004 — Sauvegarde de progression

**Contexte** : Exigence : reprise du questionnaire après fermeture du navigateur.  
**Décision (provisoire)** : Modèle prévu via `questionnaire_session` + `answer` (schéma dans le MCD). Non implémenté pour le moment. Le backend expose les tables et les entités mais la logique de reprise/restore session n'a pas été développée.  