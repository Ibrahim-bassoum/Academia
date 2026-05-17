# Academia

## Description
Academia est une application web de gestion universitaire et financière développée avec Laravel et MySQL. Elle automatise le suivi des étudiants (inscriptions, statuts) et centralise la gestion de la trésorerie (scolarités, dépenses).

## Fonctionnalités Principales
- **Gestion Académique :** Configuration des filières et des niveaux globaux.
- **Suivi des Admissions :** Inscriptions et bascule automatique des statuts étudiants.
- **Module Comptabilité :** Gestion des encaissements (frais d'inscription, tranches de scolarité) et des dépenses avec calcul du solde de caisse en temps réel.
- **Sécurité & Rôles (RBAC) :** Cloisonnement strict des accès. Redirection automatique du profil comptable vers le suivi financier (`finance/suivi`) et de l'agent d'admission vers la scolarité.

## Stack Technique
- **Backend :** Laravel 13 / PHP 8.4
- **Base de données :** MySQL
- **Droits :** Spatie Laravel-Permission