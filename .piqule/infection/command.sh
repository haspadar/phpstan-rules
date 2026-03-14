#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/infection/infection.json5"

if [ ! -f "$CONFIG" ]; then
  echo "Infection config not found: $CONFIG"
  exit 1
fi

if [ ! -d "src" ] || [ -z "$(find src -name '*.php' -maxdepth 3 | head -1)" ]; then
  echo "No PHP source files found, skipping Infection"
  exit 0
fi

INFECTION_BIN="$(.piqule/_composer.sh infection)"

XDEBUG_MODE=coverage \
  "$INFECTION_BIN" \
  --configuration="$CONFIG" \
  --threads=max
