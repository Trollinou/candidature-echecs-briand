#!/bin/bash

# Configuration
PLUGIN_SLUG="candidature-echecs-briand"
MAIN_FILE="candidature-echecs-briand.php"
DIST_IGNORE=".distignore"

# Récupération de la version depuis le fichier PHP principal
VERSION=$(grep -m 1 "Version:" $MAIN_FILE | awk '{print $NF}' | tr -d '\r')

if [ -z "$VERSION" ]; then
    echo "Erreur : Impossible de trouver la version dans $MAIN_FILE"
    exit 1
fi

ZIP_NAME="${PLUGIN_SLUG}-v${VERSION}.zip"
BUILD_DIR="dist-temp"

echo "🏗️  Compilation des assets locaux (Production)..."
if ! npm run build; then
    echo "❌ Erreur : Le build a échoué. Packaging annulé."
    exit 1
fi

echo "📦 Préparation du répertoire temporaire..."
rm -f "$ZIP_NAME"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR/$PLUGIN_SLUG"

# Construction de la commande rsync avec exclusion
RSYNC_EXCLUDES=""
if [ -f "$DIST_IGNORE" ]; then
    RSYNC_EXCLUDES="--exclude-from=$DIST_IGNORE"
fi

# Copie des fichiers vers le dossier temporaire
rsync -av . "$BUILD_DIR/$PLUGIN_SLUG/" $RSYNC_EXCLUDES --exclude="$BUILD_DIR" --exclude="*.sh" --exclude="*.zip"

echo "🤐 Création du ZIP..."
cd "$BUILD_DIR" || exit 1
zip -r "../$ZIP_NAME" "$PLUGIN_SLUG" > /dev/null
cd ..

# Nettoyage final
rm -rf "$BUILD_DIR"

echo "✅ Package créé avec succès : ${ZIP_NAME} (Environnement local préservé)"
