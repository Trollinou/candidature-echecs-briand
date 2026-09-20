# Candidature Section Échecs Collège Briand

## Description Générale
Le plugin **Candidature Section Échecs Collège Briand** gère les candidatures pour la section sportive échecs du Collège Briand (Lons-le-Saunier).
Il permet aux futurs élèves et à leurs familles de soumettre leur dossier de candidature en ligne via un formulaire dynamique ou un bloc Gutenberg natif, et au personnel administratif de consulter et exporter ces dossiers de manière sécurisée depuis l'administration de WordPress.

## Prérequis Techniques
Ce plugin utilise des technologies modernes et requiert un environnement à jour pour fonctionner correctement :
- **PHP** : 8.4 minimum (utilisation stricte du typage et des nouvelles fonctionnalités, autoloader SPL natif sans dépendance composer en production).
- **WordPress** : 7.1 minimum (support des schémas de métadonnées `register_post_meta`, de la REST API et des blocs natifs).
- **Standards de Qualité** : Conformité stricte **WordPress Coding Standards (WPCS)** et analyse statique **PHPStan (Niveau 7)**.

## Installation
1. Téléchargez ou clonez le dépôt du plugin dans le répertoire `wp-content/plugins/candidature-echecs-briand/`.
2. Si vous installez le plugin depuis les sources, vous pouvez lancer les contrôles qualité et le build :
   ```bash
   composer install
   npm install
   npm run build
   ```
3. Commandes de contrôle qualité (Dev) :
   ```bash
   composer run lint:php   # Analyse statique PHPStan Niveau 7
   composer run lint:wpcs  # Vérification du style WordPress (WPCS)
   composer run format:wpcs # Correction automatique du style (PHPCBF)
   ```
4. Activez le plugin depuis l'administration de WordPress sous l'onglet « Extensions ».

## Fonctionnalités Principales
- **Formulaire Public (Shortcode & Bloc Gutenberg)** :
  - Bloc Gutenberg natif `candidature-echecs-briand/application-form` insérable dans l'éditeur de site (FSE).
  - Shortcode `[ceb_formulaire_candidature]` disponible pour les thèmes classiques.
  - Soumission sécurisée via REST API (`/wp-json/ceb/v1/candidature`) avec repli automatique (fallback).
- **Custom Post Type & Métadonnées Typées** :
  - Type de publication personnalisé privé (`ceb_candidature`).
  - Déclaration centralisée et typée de toutes les Post Meta (`register_post_meta`) avec schémas REST et permissions.
- **Metaboxes de Consultation** : Affichage clair et structuré (en lecture seule) des données de chaque candidature dans le back-office WordPress.
- **Gestion Sécurisée des Fichiers** : Upload sécurisé des lettres de motivation avec contrôle strict des types MIME et nettoyage automatique à la suppression du dossier.
- **Export Excel / CSV** : Exportation en un clic des candidatures avec encodage UTF-8 et séparateur point-virgule compatible Excel.
