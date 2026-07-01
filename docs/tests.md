# Stratégie de tests — Trip Planner

## Framework

- **PHPUnit 13**
- Pattern **AAA** (Arrange / Act / Assert) appliqué sur tous les tests
- Couverture de code via **Xdebug 3**

## Lancer les tests

```bash
# Tous les tests
composer test

# Avec rapport de couverture HTML
composer test:coverage
```

## Structure

```
tests/
└── Unit/
    ├── RouteCalculatorTest.php
    └── BanApiClientTest.php
```

## Tests unitaires

### `RouteCalculatorTest`
Teste la formule de Haversine et l'algorithme de tri des arrêts.

| Test | Description |
|------|-------------|
| `testCalculateDistance` | Vérifie la distance Paris → Marseille (~661 km) |
| `testSortStopsModeLoin` | L'arrêt le plus éloigné est placé en dernier |
| `testSortStopsModePres` | L'arrêt le plus proche est placé en dernier |

### `BanApiClientTest`
Teste l'appel HTTP vers l'API BAN avec un `MockHttpClient`.

| Test | Description |
|------|-------------|
| `testSearchReturnsCoordinates` | Retourne les coordonnées GPS d'une adresse valide |
| `testSearchReturnsNullOnEmptyResult` | Retourne `null` si l'API ne trouve rien |

## Couverture

- Couverture actuelle : **100%** sur les services métier
- Seuil minimum requis : **60%**
- Rapport HTML généré dans : `var/coverage/`

## Bonnes pratiques appliquées

- Les appels HTTP vers l'API BAN sont **mockés** (pas d'appel réseau réel en test)
- Aucun test ne dépend de la base de données ou d'un service externe
- Chaque test est **isolé** et **reproductible**
