# Candidature Section Échecs Collège Briand

## Description Générale
Le plugin **Candidature Section Échecs Collège Briand** gère les candidatures pour la nouvelle section sportive échecs (Ouverture Septembre 2026).
Il permet aux futurs élèves de soumettre leur dossier de candidature en ligne via un formulaire dédié, et au personnel administratif de consulter ces dossiers de manière sécurisée depuis l'administration de WordPress.

## Prérequis Techniques
Ce plugin utilise des technologies modernes et requiert un environnement à jour pour fonctionner correctement :
- **PHP** : 8.4 minimum (utilisation stricte du typage et des nouvelles fonctionnalités).
- **WordPress** : 6.9 minimum.

## Installation
1. Téléchargez ou clonez le dépôt du plugin dans le répertoire `wp-content/plugins/candidature-echecs-briand/`.
2. Si vous installez le plugin depuis les sources, vous devez obligatoirement compiler les assets :
   ```bash
   npm install
   npm run build
   ```
   *(Note : Node.js 20 LTS est requis pour cette étape).*
3. Activez le plugin depuis l'administration de WordPress sous l'onglet "Extensions".

## Fonctionnalités Principales
- **Formulaire Public** : Formulaire d'inscription front-end accessible via shortcode permettant de collecter les données de l'élève, du représentant légal, du parcours échiquéen et des motivations.
- **Custom Post Type Privé** : Un type de publication personnalisé (`ceb_candidature`) pour stocker de manière isolée et sécurisée les candidatures reçues.
- **Metaboxes de Consultation** : Affichage clair et structuré (en lecture seule) des données de chaque candidature dans le back-office WordPress, facilitant l'étude des dossiers par l'administration du collège.
- **Gestion Sécurisée des Fichiers** : Upload sécurisé de lettres de motivation ou documents justificatifs, avec contrôle strict des formats et de la sécurité des données.
