# Academia

Application web Laravel de gestion universitaire et financière. Academia centralise le suivi des étudiants et les opérations de trésorerie dans une même plateforme.

## Fonctionnalités

- gestion des étudiants et des inscriptions ;
- suivi des statuts et des dossiers scolaires ;
- gestion des frais de scolarité ;
- suivi des dépenses et de la caisse ;
- contrôle d'accès par rôles et permissions ;
- espaces adaptés aux agents et aux comptables ;
- génération de documents PDF.

## Stack technique

- PHP 8.3+ ;
- Laravel 13 ;
- Laravel Breeze ;
- Spatie Laravel Permission ;
- DomPDF ;
- Vite et Tailwind CSS ;
- MySQL ou SQLite selon l'environnement.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```

Pour lancer les tests :

```bash
php artisan test
```

## Sécurité

Ne commitez jamais le fichier `.env`. Configurez les rôles, permissions et identifiants de base de données dans l'environnement d'exécution.

## Statut

Projet en développement actif.

## Auteur

[Ibrahim Bassoum](https://github.com/Ibrahim-bassoum)
