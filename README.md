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
docker-compose up -d --build
```

## Endpoints Exposé

- Symfony : http://localhost:8000  
- Next.js : http://localhost:3000  
- Adminer : http://localhost:7080  

Postgres est exposé sur le port 5432.  

## Créer la base de données et charger les fixtures

```bash
docker-compose exec backend composer run db
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