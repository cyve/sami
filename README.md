# SAMI - Documentation technique

## Installation
    $ composer require cyve/sami

Ajouter les lignes suivantes au fichier composer.json
    "scripts": {
        "sami": ["@php vendor/cyve/sami/bin/sami.phar update vendor/cyve/sami/config.php"]
    }

## Génération de la documentation
    $ cd sami && php sami.phar update config.php

ou
    $ composer sami

La documentation est créée dans le dossier "documentation" à la racine du projet.

## Documentation technique de la documentation technique

- Le fichier "config.php" contient la configuration de Sami
- Le dossier "scripts" contient les scripts PHP personnalisés qui permettent de générer les index de recherche des services et des routes
- Le dossier "themes/cyve" contient la surcharge du theme qui contient les index de recherche personnalisés

## Ressources

https://github.com/FriendsOfPHP/Sami
https://github.com/cyve/sami
