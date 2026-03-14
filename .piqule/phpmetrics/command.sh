#!/usr/bin/env bash
set -euo pipefail

CONFIG=".piqule/phpmetrics/config.json"
VERIFY=".piqule/phpmetrics/verify.php"

if [ ! -f "$CONFIG" ]; then
  echo "PHPMetrics config not found: $CONFIG"
  exit 1
fi

BIN="$(.piqule/_composer.sh phpmetrics)"

php -d error_reporting='E_ALL & ~E_DEPRECATED' \
  "$BIN" \
  --config="$CONFIG"

if [ -f "$VERIFY" ]; then
  php "$VERIFY"
fi

