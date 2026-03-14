#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpcs/phpcs.xml"

if [ ! -f "$CONFIG" ]; then
  echo "PHPCS config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh phpcs)"

"$BIN" --standard="$CONFIG"
