# Architecture — Trip Planner

## Vue d'ensemble

Trip Planner est une application monolithique Symfony 7 suivant le pattern MVC,
sans framework JavaScript côté client.

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Langage | PHP 8.2 |
| Framework | Symfony 7 |
| Templates | Twig |
| Base de données | PostgreSQL (Supabase) |
| ORM | Doctrine |
| Conteneur | FrankenPHP (Docker) |
| API externe | Base Adresse Nationale (BAN) |

## Structure des dossiers

```
trip-planner/
├── src/
│   ├── Controller/       # Contrôleurs Symfony
│   ├── Service/          # Logique métier
│   │   ├── RouteCalculator.php   # Algorithme Haversine
│   │   └── BanApiClient.php      # Appels API BAN
│   └── Entity/           # Entités Doctrine
├── templates/            # Vues Twig
├── docs/                 # Documentation du projet
├── tests/                # Tests PHPUnit
├── docker-compose.yml
└── Dockerfile
```

## Flux applicatif

```
Utilisateur
    │
    ▼
[Page 1] Saisie adresse de départ (autocomplétion BAN)
    │
    ▼
[Page 2] Saisie des arrêts + mode ("Loin" / "Proche")
    │
    ▼
[BanApiClient] Géocodage de chaque adresse → coordonnées GPS
    │
    ▼
[RouteCalculator] Calcul distances (Haversine) + tri des arrêts
    │
    ▼
[Page 3] Feuille de route ordonnée + liens Waze / Google Maps
```

## Services métier

### `RouteCalculator`
- Calcule la distance entre deux points GPS via la formule de Haversine
- Trie les arrêts intermédiaires
- Applique la règle "Loin" / "Proche" pour déterminer l'arrêt final

### `BanApiClient`
- Interroge l'API BAN : `https://api-adresse.data.gouv.fr/search/`
- Retourne les coordonnées GPS d'une adresse française
- Utilise le composant `HttpClient` de Symfony (mockable pour les tests)

## Base de données

Hébergée sur Supabase (PostgreSQL managé).
Connexion via la variable d'environnement `DATABASE_URL`.
Migrations gérées avec Doctrine Migrations.
