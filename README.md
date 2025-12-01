# Test-Technique

Ce projet contient :

- Backend : Symfony 7.1 + API Platform
- Frontend : Next.js + Node 20
- Base de données : PostgreSQL 
- Adminer pour gérer la base

## Prérequis

- [Docker](https://www.docker.com/get-started) et [Docker Compose](https://docs.docker.com/compose/install/)
- Git (pour cloner le projet)

## Cloner le projet 

```bash
git clone https://github.com/LilyLoki/Test-technique.git
```

## Lancer les services

Depuis la racine du projet :

```bash
docker compose up -d --build
```

## Endpoints Exposé

- Symfony : http://localhost:8000  
- Next.js : http://localhost:3000  
- Adminer : http://localhost:7080  

Postgres est exposé sur le port 5432. 

## Generer les assets Api Platform

```bash
docker compose exec backend php bin/console assets:install public
```

## Créer la base de données et charger les fixtures

```bash
docker compose exec backend composer db
```

## Effectuer les tests unitaire et d'intégration

```bash
docker compose exec backend composer test
```

## Arrêter et supprimer les services

Arrêter les conteneurs :  
```bash
docker-compose down 
```
Supprimer les conteneurs + volumes :  
```bash
docker-compose down -v
```

## Utilisateurs :
- **admin** username : admin1, password : testadmin
- **user** username : user1, password : testuser

## Scripts
### Symfony :

```composer install```  
Installe le projet.

#### Qualité & Formatage

```composer test:csfixer```  
Vérifie que le code respecte les standards CS Fixer.

```composer fix:csfixer```  
Corrige automatiquement le code avec CS Fixer.

#### Analyse statique

```composer test:phpstan```  
Lance l'analyse statique du code avec PHPStan.

#### Tests

```composer test:phpunit```  
Exécute les tests PHPUnit.

```composer test```  
Exécute l’ensemble des tests définis pour le projet.

#### Base de données

```composer db```  
Exécute les opérations liées à la base (migrations, etc. selon configuration).

#### Démarrer le projet

```composer start```
Lance le serveur Symfony.  

### NextJs :

```npm install```  
Installe le projet.

#### Développement

```npm run dev```  
Lance l'application Next.js en mode développement.

#### Build & Production

```npm run build```  
Génère la version optimisée de l’application.

```npm run start```  
Lance Next.js en mode production (nécessite un build préalable).

#### Lint (ESLint)

```npm run lint```  
Analyse le code sans le modifier.

```npm run lint:fix```  
Corrige automatiquement les problèmes détectés par ESLint.

#### Formatage (Prettier)

```npm run format```  
Vérifie le formatage via Prettier.

```npm run format:fix```
Reformate automatiquement les fichiers.

#### Tests

```npm run test```
Exécute ESlint et Prettier.