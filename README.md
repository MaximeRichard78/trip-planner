# Trip Planner

Application de planification de trajets français avec ordonnancement intelligent des arrêts.

![CI](https://github.com/MaximeRichard78/trip-planner/actions/workflows/ci.yml/badge.svg)

## Stack

- PHP 8.5 / Symfony 7 / Twig
- PostgreSQL (Supabase)
- Docker : FrankenPHP
- API : Base Adresse Nationale (BAN)

## Pré-requis

- Docker installé
- PHP 8.5 + Composer (dev local)
- Symfony CLI 5.7+

## Lancer le projet

```bash
git clone <https://github.com/MaximeRichard78/trip-planner.git>
cd trip-planner
cp .env.example .env
# Remplir les variables dans .env
docker compose up
```

L'app est accessible sur http://localhost:80

## Tester

```bash
composer test
```

## Architecture

Voir [docs/architecture.md](docs/architecture.md)

## Auteur

Prénom Nom — promotion M1SI 2025/2026