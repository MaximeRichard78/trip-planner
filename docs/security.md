# Risques de sécurité DevOps — trip-planner

## Risque 1 : Exposition de secrets dans le dépôt Git
**Description :** Un fichier `.env` contenant de vraies credentials (DATABASE_URL, APP_SECRET)
pourrait être commité accidentellement.
**Impact :** Accès non autorisé à la base de données Supabase.
**Mitigation :** `.env` dans `.gitignore`, GitHub Secret Scanning + Push Protection activés,
secrets stockés dans GitHub Secrets.

## Risque 2 : Dépendances Composer vulnérables
**Description :** Les packages PHP (Symfony, Doctrine…) peuvent contenir des CVE connues.
**Impact :** Exploitation de failles dans les bibliothèques tierces.
**Mitigation :** Dependabot alerts + security updates activés, `composer audit` intégré en CI.

## Risque 3 : Injection via les paramètres de requête
**Description :** Les adresses saisies par l'utilisateur sont transmises à l'API BAN et à Doctrine.
**Impact :** Injection SQL ou manipulation de requêtes HTTP.
**Mitigation :** Utilisation des requêtes préparées Doctrine, validation et échappement des entrées
côté serveur avant tout appel externe.

## Risque 4 : Exposition de l'image Docker avec des secrets intégrés
**Description :** Des variables d'environnement sensibles pourraient être figées dans le Dockerfile
ou l'image buildée.
**Impact :** Récupération des secrets par inspection de l'image (`docker inspect`).
**Mitigation :** Secrets injectés uniquement à l'exécution via `docker-compose.yml` + fichier `.env`
local non commité, jamais dans le `Dockerfile`.

## Risque 5 : Absence de HTTPS en production
**Description :** Le trafic entre l'utilisateur et l'application pourrait transiter en HTTP clair.
**Impact :** Interception des données (man-in-the-middle), notamment les coordonnées GPS.
**Mitigation :** FrankenPHP gère automatiquement HTTPS via Let's Encrypt en production.