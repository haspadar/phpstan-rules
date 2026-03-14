#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpstan/phpstan.neon"

if [ ! -f "$CONFIG" ]; then
  echo "PHPStan config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ]; then
  echo "No src directory found, skipping PHPStan"
  exit 0
fi

BIN="$(.piqule/_composer.sh phpstan)"

"$BIN" analyse \
  -c "$CONFIG" \
  --memory-limit=1G
