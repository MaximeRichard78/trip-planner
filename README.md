# Trip Planner

Application de planification de trajets français avec ordonnancement intelligent des arrêts.

![CI](https://github.com/MaximeRichard78/trip-planner/actions/workflows/ci.yml/badge.svg)

## Stack

- PHP 8.5 / Symfony 7 / Twig
- PostgreSQL (Supabase)
- Docker : FrankenPHP
- API : Base Adresse Nationale (BAN)

## Pré-requis

- Docker Desktop installé et lancé
- PHP 8.5 + Composer (dev local)
- Symfony CLI 5.7+

## 🐳 Lancement avec Docker

### Configuration

```bash
git clone https://github.com/MaximeRichard78/trip-planner.git
cd trip-planner
cp .env.example .env
```

Remplissez `DATABASE_URL` dans `.env` avec votre chaîne de connexion Supabase (Project Settings → Database → Connect → Direct connection). Ce fichier n'est jamais commité.

### Démarrage en développement

```bash
docker compose up -d --build
```

Le `compose.override.yml` est chargé automatiquement et active le hot-reload : votre code local est monté dans le conteneur, les dépendances dev (PHPUnit, MakerBundle, etc.) sont installées, et le mode debug Symfony est actif.

L'application est accessible sur **http://localhost:8080**.

### Démarrage en production (image seule)

```bash
docker compose -f docker-compose.yml up -d --build
```

Cette commande ignore `compose.override.yml` et utilise l'image de production optimisée (`composer install --no-dev`).

### Vérifier l'état du conteneur

```bash
docker compose ps
docker compose logs app --tail=50
```

Le conteneur doit afficher le statut `healthy` après ~30s.

### Arrêter les conteneurs

```bash
docker compose down
```

Ajoutez `-v` pour supprimer aussi les volumes (utile si le `vendor/` du conteneur doit être réinitialisé) :
```bash
docker compose down -v
```

### ⚠️ Pièges connus

- L'image Docker utilise **PHP 8.4** (et non 8.5 comme en local) car `composer.lock` exige PHP ≥ 8.4 pour les dépendances de test (PHPUnit 13).
- Le `.env` à la racine doit contenir une vraie chaîne de connexion Supabase pour `DATABASE_URL` — ne jamais commiter ce fichier avec de vraies valeurs.
- Composer n'est pas inclus nativement dans l'image `dunglas/frankenphp` ; il est copié depuis l'image officielle `composer:2`.

## Tester

```bash
composer test
```

## Architecture

Voir [docs/architecture.md](docs/architecture.md)

## Auteur

Maxime Richard — 2025/2026