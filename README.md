# 🧩 TaskBoard Pro — Projet de révision Symfony 7 (PHP 8.4 + FrankenPHP)

Ce projet sert de support à la préparation de la **certification Symfony 7**, en fournissant un environnement complet, reproductible et industriel.  
Il repose sur **FrankenPHP**, **Docker**, **PostgreSQL**, et une intégration continue GitHub Actions.

---

## ⚙️ 1. Stack technique

| Composant                       | Description |
|---------------------------------|-------------|
| [FrankenPHP](https://frankenphp.dev) | Serveur PHP moderne (HTTP server + worker intégré) |
| PHP 8.4                         | Environnement d’exécution |
| PostgreSQL 16                   | Base de données principale |
| Docker / Compose                | Conteneurisation |
| Symfony 7.0                     | Framework principal |
| PHPStan                         | Analyse statique |
| PHP CS Fixer                    | Formatage du code |
| PHPUnit                         | Tests unitaires |
| Makefile                        | Automatisation locale |
| GitHub Actions                  | Intégration continue |

---

## 🚀 2. Installation

### Prérequis
- Docker Compose ≥ 2.10
- GNU Make
- Git

### Étapes

```bash
# 1. Cloner le dépôt
git clone https://github.com/thomas-cautres/TaskBoard-Pro.git
cd TaskBoard-Pro

# 2. Construire et démarrer les conteneurs
make start

# 3. Vérifier l'accès
open https://localhost
```

##  🧰 3. Commandes Make

| Commande                | Description                                                                       |
|-------------------------|-----------------------------------------------------------------------------------|
| `make build`            | Reconstruit les images Docker sans cache                                          |
| `make up`               | Démarre les conteneurs                                                            |
| `make down`             | Stoppe les conteneurs et supprime les orphelins                                   |
| `make logs`             | Affiche les logs en temps réel                                                    |
| `make sh`               | Ouvre un shell dans le conteneur FrankenPHP                                       |
| `make bash`             | Shell Bash avec historique des commandes                                          |
| `make composer c="..."` | Exécute une commande Composer (ex : `make composer c='require symfony/orm-pack'`) |
| `make vendor`           | Installe les dépendances (`composer install`)                                     |
| `make sf c="..."`       | Exécute une commande Symfony CLI (ex : `make sf c=about`)                         |
| `make cc`               | Vide le cache Symfony                                                             |
| `make db`               | Créé la base de données et les tables                                             |
| `make test`             | Lance les tests PHPUnit (`APP_ENV=test`)                                          |
| `make phpstan`          | Lance l’analyse statique PHPStan                                                  |
| `make phpcsfixer`       | Exécute PHP CS Fixer pour corriger le code                                        |
| `make help`             | Affiche la liste des commandes disponibles                                        |           

