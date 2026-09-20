# Guide d'Utilisation : Candidature Section Échecs Collège Briand

Ce guide est destiné à l'administration du Collège Briand (Webmasters, Professeurs, Secrétariat) afin de comprendre l'utilisation du plugin de gestion des candidatures pour la section sportive échecs.

## 1. Intégrer le Formulaire de Candidature sur le Site

Pour permettre aux élèves et à leurs familles de soumettre une candidature en ligne, vous pouvez intégrer le formulaire de deux façons :

### Méthode A : Bloc Gutenberg (Recommandée pour l'éditeur de blocs et FSE)
1. Créez ou modifiez une page via l'éditeur WordPress (ex: « Inscription Section Échecs »).
2. Cliquez sur le bouton « + » d'ajout de bloc et recherchez **« Formulaire Candidature Section Échecs »**.
3. Insérez le bloc sur votre page et enregistrez.

### Méthode B : Shortcode (Thèmes classiques)
1. Créez ou modifiez une page via l'éditeur de votre site WordPress.
2. Ajoutez le code court (Shortcode) suivant :
   ```text
   [ceb_formulaire_candidature]
   ```
3. Publiez ou mettez à jour votre page.

Le formulaire apparaîtra à cet emplacement exact, invitant les candidats à remplir leurs coordonnées, leur niveau échiquéen, et à joindre leur lettre de motivation.

---

## 2. Consulter les Dossiers de Candidature Soumis

Chaque fois qu'un formulaire est complété et validé, un dossier de candidature est automatiquement créé dans l'administration de WordPress.

### Accéder à la liste des candidatures
- Dans le menu latéral gauche de l'administration WordPress, cliquez sur **« Candidatures Échecs »**.
- Vous verrez la liste complète de toutes les candidatures déposées, incluant : le nom de l'élève (Candidat), l'email du représentant légal, l'année de rentrée, la classe actuelle, la classe ciblée, le niveau aux Échecs, et la date de soumission.
- Vous pouvez filtrer les candidatures par **Année de rentrée** grâce au sélecteur en haut de tableau.

### Lire le détail d'un dossier
- Cliquez sur le **Titre** d'une candidature pour ouvrir le détail du dossier.
- Les informations sont réparties dans **trois encadrés (Metaboxes) en lecture seule** :
  - **1. Identité de l'élève** : Nom, prénom, date de naissance, établissement actuel, classes, LV1, LV2.
  - **2. Représentant Légal** : Coordonnées complètes du responsable 1 (et responsable 2 le cas échéant).
  - **3. Parcours Échiquéen & Motivation** : Année de début, club, niveau, compétitions, titres, et motivation.

### Télécharger la Lettre de Motivation (PDF / Image)
Dans l'encadré **Parcours Échiquéen & Motivation**, si le candidat a joint un fichier de motivation, un bouton bleu **« Télécharger la lettre de motivation »** permet d'ouvrir ou d'enregistrer le fichier directement.

---

## 3. Exporter les Candidatures vers Excel

Pour extraire et analyser l'ensemble des candidatures dans un tableur (Microsoft Excel, Google Sheets, LibreOffice Calc) :

1. Dans l'administration de WordPress, cliquez sur **« Candidatures Échecs »**.
2. Cliquez sur le bouton **« Exporter vers Excel »** situé en haut de la liste (à côté du filtre par année).
3. Le téléchargement d'un fichier `.csv` débutera immédiatement.
4. Ouvrez le fichier dans Excel : les caractères accentués en français et la mise en forme des colonnes sont pris en charge nativement (encodage UTF-8 avec BOM et séparateur point-virgule).

*(Note : Conformément aux exigences de confidentialité, le fichier exporté ne contient pas les lettres de motivation (qu'elles soient rédigées ou sous forme de fichier joint)).*
