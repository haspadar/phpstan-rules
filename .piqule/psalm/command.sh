#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/psalm/psalm.xml"

if [ ! -f "$CONFIG" ]; then
  echo "Psalm config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh psalm)"

"$BIN" \
  --root=. \
  --config="$CONFIG" \
  --no-cache
