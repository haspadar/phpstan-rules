#!/usr/bin/env bash
set -euo pipefail

if ! command -v docker >/dev/null 2>&1; then
  echo "Error: docker is not installed or not in PATH" >&2
  exit 1
fi

PROJECT_ROOT="$(pwd)"

IMAGE="${PIQULE_INFRA_IMAGE:-ghcr.io/haspadar/piqule-infra@sha256:a7d41e9fef08156778df6f9172145970a617962bee9e17f1484ebc9b41f6ac29}"

docker run --rm \
  --user "$(id -u):$(id -g)" \
  -v "$PROJECT_ROOT:/project" \
  -w /project \
  "$IMAGE" \
  "$@"
