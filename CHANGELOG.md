# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/),
et ce projet adhère au [Gestion sémantique de version](https://semver.org/spec/v2.0.0.html).

## [1.0.2] - 2026-06-02

### Ajouté
- **Export Excel / CSV** : Ajout de la fonctionnalité d'exportation de toutes les candidatures au format CSV (compatible Excel, séparateur point-virgule et UTF-8 BOM) sans les lettres de motivation (qu'elles soient textuelles ou jointes).
- **Bouton d'exportation** : Ajout d'un bouton « Exporter vers Excel » à côté du sélecteur d'année dans la vue d'administration des candidatures.

## [1.0.1] - 2026-06-02

### Ajouté
- **Colonne Email** : Ajout d'une colonne affichant l'adresse e-mail du représentant légal (cliquable via `mailto:`) en deuxième position de la liste des candidats dans le back-office.

## [1.0.0] - 2026-03-28

### Ajouté
- **CPT `ceb_candidature`** : Ajout d'un type de publication personnalisé (Custom Post Type) privé pour gérer de manière isolée et sécurisée les candidatures reçues.
- **Formulaire d'inscription front-end** : Ajout d'un formulaire public permettant aux futurs élèves de soumettre leur dossier de candidature en ligne via le shortcode `[ceb_formulaire_candidature]`.
- **Gestion des uploads de motivation** : Implémentation de la gestion sécurisée des uploads de fichiers de motivation avec validation stricte des extensions autorisées (pdf, doc, docx, oft, rtf).
- **Metaboxes de consultation back-office** : Ajout de métaboîtes (Metaboxes) en lecture seule dans l'administration de WordPress permettant au personnel de consulter de manière claire et structurée les dossiers de candidatures.
