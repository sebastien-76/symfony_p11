README.md
#Symfony P11

Ce repo contient une application de gestion de formation.
Il s'agit d'un projet pédagogique pour la promo 11.

## Prérequis

- Linux, MacOS, Windows
- Bash
- PHP 8
- Composer
- Symfony-cli
- Mariadb 10
- Docker( optionnel)

## Installation

```
git clone https://github.com/sebastien-76/symfony_p11
cd symfony_p11
composer install

```
Créer une base de données et un utilisateur dédié pour cette base de données.

## Configuration

Créer un fichier `.env.local` à la racine du projet

```
APP_ENV=dev
APP_DEBUG=true
APP_SECRET=123
DATABASE_URL="mysql://symfony_p11:123@127.0.0.1:3306/symfony_p11?serverVersion=mariadb-10.6.12&charset=utf8mb4"
```

Penser  à changer la variable `APP_SECRET` et les codes d'accès dans la varaible `DATABASE_URL`.

## Migration et fixtures

Pour que l'application soit utilisable, il faut créer le schéma de la base de données et charger les données.

```
bin/dofilo.sh
```




**ATTENTION : `APP_SECRET` doit être une chaîne de caractère de 32 caractères en hexadécimal.**

## Utilisation

Lancer le serveur web de développement :

```
symfony serve
```

Puis ouvrir la page suivante : [https://localhost:8080](https://localhost:8080)

## Mentions légales

Ce projet est sous licence MIT.

La licence est disponible ici : [MIT LICENCE](LICENCE)

