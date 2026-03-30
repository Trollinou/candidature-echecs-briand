# Guide d'Utilisation : Candidature Section Échecs Collège Briand

Ce guide est destiné à l'administration du Collège Briand (Webmasters, Professeurs, Secrétariat) afin de comprendre l'utilisation du plugin de gestion des candidatures pour la section sportive échecs.

## 1. Intégrer le Formulaire de Candidature sur le Site

Pour permettre aux élèves et à leurs familles de soumettre une candidature en ligne, vous devez intégrer le formulaire sur une page dédiée.

- **Étape 1** : Créez ou modifiez une page via l'éditeur de votre site WordPress (ex: "Inscription Section Échecs").
- **Étape 2** : Ajoutez le "bloc Court code" (Shortcode) suivant dans le contenu de la page :
  ```text
  [ceb_formulaire_candidature]
  ```
- **Étape 3** : Publiez ou mettez à jour votre page. Le formulaire apparaîtra à cet emplacement exact, invitant les utilisateurs à remplir leurs coordonnées, leur niveau échiquéen, et à joindre leur lettre de motivation.

## 2. Consulter les Dossiers de Candidature Soumis

Chaque fois qu'un formulaire est complété et soumis sur le site, un dossier de candidature est automatiquement créé dans l'administration de WordPress.

### Accéder à la liste des candidatures
- Dans le menu latéral gauche de l'administration WordPress, cliquez sur **"Candidatures Échecs"**.
- Vous verrez la liste complète de toutes les candidatures déposées, avec le nom de l'élève, sa classe actuelle, et la date de soumission.

### Lire le détail d'un dossier
- Cliquez sur le **Titre** d'une candidature pour ouvrir le détail du dossier.
- Les informations sont réparties dans **trois encadrés (Metaboxes) en lecture seule** :
  - **Identité du Candidat** : Nom, prénom, date de naissance, etc.
  - **Représentant Légal** : Nom du parent, coordonnées (email, téléphone), adresse complète, etc.
  - **Parcours Échiquéen et Motivation** : Expérience échiquéenne (début, niveau, club), et les motivations de l'élève.

### Télécharger la Lettre de Motivation (PDF)
Dans l'encadré **Parcours Échiquéen et Motivation**, si l'élève a fourni un fichier PDF joint (comme la lettre de motivation), un bouton de téléchargement sera disponible.
- Cliquez simplement sur le lien **Télécharger la Lettre de Motivation (PDF)** pour enregistrer et consulter le document sur votre ordinateur.
