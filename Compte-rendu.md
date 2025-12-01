# Rapport technique – Application de questionnaires interactifs

## 1. Modélisation de l’arbre décisionnel

La structure du questionnaire repose sur trois entités centrales : **Questionnaire**, **Question**, et **Choice**.  
Cette modélisation permet de représenter un arbre de décision flexible, où chaque réponse oriente l’utilisateur vers la question suivante.

### Structure générale

- **Questionnaire**  
  Contient les métadonnées (titre, description, date de création) et permet d’héberger plusieurs arbres indépendants.

- **Question**  
  - Rattachée à un questionnaire.  
  - Contient le texte de la question et éventuellement un média attaché (`mediaType`, `mediaUrl`).  
  - Le champ `isRoot` identifie la question de départ.

- **Choice**  
  - Chaque choix appartient à une question.  
  - `nextQuestionId` permet de pointer vers une question enfant.  
  - Si `nextQuestionId` est `null`, le choix correspond à une **fin de branche**.

### Avantages du modèle

- L’enchaînement Question → Choice → Question modélise naturellement un arbre de décision.
- Les fins de parcours sont explicites.
- Le champ `isRoot` simplifie l’accès à la question initiale.

### Limites de la modélisation actuelle

- Aucun mécanisme automatique d’empêchement des cycles.
- Les entités liées à la progression (`questionnaire_session`, `answer`) sont modélisées mais non implémentées dans le backend.

---

## 2. Choix d’architecture

### 2.1 Architecture générale

L’application est organisée en services indépendants, chacun exécuté dans son conteneur Docker :

- **Frontend** : Next.js (TypeScript)  
- **Backend** : Symfony + API Platform  
- **Base de données** : PostgreSQL  
- **Adminer** pour la visualisation de la base  
- Un `docker compose up` unique démarre l’ensemble de l’environnement.

### 2.2 Backend

- API générée via **API Platform**, exposant les CRUD de :
  - Questionnaire  
  - Question  
  - Choice  
  - User  
- Authentification par **JWT**, avec route `/auth`.  
- Route `api/me` permettant d’obtenir l’utilisateur courant.  
- Gestion simple des médias via `mediaType` et `mediaUrl` dans l’entité Question.

### 2.3 Frontend

- Développé en Next.js, communiquant avec l’API Symfony.
- Le **parcours répondant** est fonctionnel :
  - récupération de la question courante,
  - affichage des choix,
  - enchaînement dynamique vers la question suivante.

L’interface d’administration n’a pas pu être réalisée.

### 2.4 Gestion de la progression

- Les entités nécessaires existent dans la modélisation (`questionnaire_session`, `answer`).  
- La fonctionnalité n’est cependant **pas implémentée** dans le backend ni dans le frontend.

---

## 3. Fonctionnalités réalisées

### Fonctionnelles
- Navigation dynamique dans un arbre décisionnel complet.  
- CRUD opérationnels via API Platform.  
- Authentification JWT.  
- Upload basique de médias pour les questions.  
- Architecture multi-services sous Docker.  
- Docker Compose fonctionnel.

### Non réalisées / partielles
- Interface d’administration complète.  
- Gestion des rôles utilisateurs.  
- Sauvegarde et reprise de progression.  
- Historique des réponses et sessions utilisateur.  
- Monitoring et tableau de bord.

---

## 4. Limites connues

1. **Absence d’interface d’administration**  
   Impossible pour un administrateur non technique de gérer l’arbre.

2. **Rôles utilisateurs non exploités**  
   Pas de restriction d’accès sur l’administration.

3. **Pas de persistance de la progression**  
   L’utilisateur doit recommencer s’il ferme la page.

4. **Pas de contrôle de cohérence des arbres**  
   Risque de création de cycles.

5. **Courbe d’apprentissage Next.js**  
   Temps perdu sur la comprehension et l'utilisation de Next.js.

---

## 5. Pistes d’amélioration

### 5.1 Fonctionnelles
- Interface graphique d’administration complète (création, édition, visualisation).  
- Mise en place de la reprise de progression :
  - Sessions backend pour utilisateurs authentifiés,
  - LocalStorage + synchronisation pour anonymes.

### 5.2 Techniques
- Implémentation de `questionnaire_session` et `answer`.  
- Ajout d’un validateur empêchant les boucles dans l’arbre.  
- Rôle administrateur réellement exploité côté frontend.

### 5.3 Observabilité & Monitoring
- Ajout de **Grafana** pour suivre :
  - taux de complétion,  
  - temps moyen de réponse,  
  - abandon par question.

---