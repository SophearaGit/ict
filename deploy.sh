#!/bin/bash
# ict-lms deploy script — one command instead of copy/pasting the
# server-update sequence by hand every time.
#
# One-time setup on the server:
#   chmod +x deploy.sh
#
# Every deploy after that:
#   ./deploy.sh
#
set -e

cd "$(dirname "$0")"

echo "==> [1/6] Checking for local (server-side) changes..."
if [ -n "$(git status --porcelain)" ]; then
    git add .
    git commit -m "server changes: $(date +'%Y-%m-%d %H:%M:%S')"
    echo "    Committed local changes."
else
    echo "    Nothing to commit."
fi

echo "==> [2/6] Pulling latest code from origin/master..."
BEFORE_HASH=$(git rev-parse HEAD)
# --no-edit accepts the default merge-commit message automatically —
# this is the part that used to need Ctrl+O / Enter / Ctrl+X in nano.
git pull --no-rebase --no-edit origin master
AFTER_HASH=$(git rev-parse HEAD)

echo "==> [3/6] Installing PHP dependencies..."
composer install --no-interaction

echo "==> [4/6] Installing JS dependencies..."
npm install

echo "==> [5/6] Clearing caches..."
php artisan optimize:clear

echo "==> [6/6] Building frontend assets..."
npm run build

if [ "$BEFORE_HASH" != "$AFTER_HASH" ] && git diff --name-only "$BEFORE_HASH" "$AFTER_HASH" | grep -q '^database/migrations/'; then
    echo
    echo "==> Migration files changed in this pull — running migrations..."
    # --force is required for artisan to run migrations non-interactively
    # when APP_ENV=production; without it, Laravel stops and waits for a
    # confirmation prompt that never comes in a script.
    php artisan migrate --force
else
    echo
    echo "==> No migration changes detected — skipping php artisan migrate."
fi

echo
echo "Deploy complete."
