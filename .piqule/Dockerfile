# syntax=docker/dockerfile:1

ARG PHP_IMAGE=php:8.3-cli-bookworm
ARG NODE_IMAGE=node:24.12.0-bookworm

# ============================================================
# Linters
# ============================================================
ARG ACTIONLINT_VERSION=1.7.10
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG YAMLLINT_VERSION=1.37.1
ARG TYPOS_VERSION=1.41.0

# ============================================================
# PMD (CPD)
# ============================================================
ARG PMD_VERSION=7.20.0

# ============================================================
# Node.js stage
# ============================================================
FROM ${NODE_IMAGE} AS node

# ============================================================
# PHP base stage
# ============================================================
FROM ${PHP_IMAGE}

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Piqule — PHP Quality Laws"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ------------------------------------------------------------
# Re-declare ARGs
# ------------------------------------------------------------
ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG TYPOS_VERSION
ARG PMD_VERSION

# ------------------------------------------------------------
# Node.js runtime
# ------------------------------------------------------------
COPY --from=node /usr/local /usr/local
ENV PATH="/usr/local/bin:/usr/local/lib/node_modules/.bin:${PATH}"

# ------------------------------------------------------------
# Unified build step
# ------------------------------------------------------------
RUN set -eux; \
    \
    # -------------------------------------------------------- \
    # System packages \
    # -------------------------------------------------------- \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        bash \
        git \
        curl \
        unzip \
        fish \
        python3 \
        python3-venv \
        pipx \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
        openjdk-17-jre-headless; \
    rm -rf /var/lib/apt/lists/*; \
    \
    # -------------------------------------------------------- \
    # PHP extensions \
    # -------------------------------------------------------- \
    docker-php-ext-install intl zip mbstring; \
    \
    # -------------------------------------------------------- \
    # Architecture detection \
    # -------------------------------------------------------- \
    ARCH="$(uname -m)"; \
    case "$ARCH" in \
      x86_64) \
        ACTIONLINT_ARCH="amd64"; \
        HADOLINT_ARCH="x86_64"; \
        TYPOS_ARCH="x86_64" ;; \
      aarch64) \
        ACTIONLINT_ARCH="arm64"; \
        HADOLINT_ARCH="arm64"; \
        TYPOS_ARCH="aarch64" ;; \
      *) echo "Unsupported architecture: $ARCH" >&2; exit 1 ;; \
    esac; \
    \
    # -------------------------------------------------------- \
    # Composer \
    # -------------------------------------------------------- \
    EXPECTED_CHECKSUM="$(curl -sS https://composer.github.io/installer.sig)"; \
        curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php; \
        ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"; \
        [ "$EXPECTED_CHECKSUM" = "$ACTUAL_CHECKSUM" ] || { echo "Installer corrupt"; exit 1; }; \
        php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer; \
        rm /tmp/composer-setup.php; \
    export COMPOSER_ALLOW_SUPERUSER=1; \
    export COMPOSER_HOME=/usr/local/composer; \
    \
    # -------------------------------------------------------- \
    # actionlint \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/actionlint; \
    \
    # -------------------------------------------------------- \
    # markdownlint-cli2 \
    # -------------------------------------------------------- \
    npm install -g "markdownlint-cli2@${MARKDOWNLINT_VERSION}"; \
    npm cache clean --force; \
    \
    # -------------------------------------------------------- \
    # yamllint \
    # -------------------------------------------------------- \
    pipx install "yamllint==${YAMLLINT_VERSION}"; \
    \
    # -------------------------------------------------------- \
    # hadolint \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    # -------------------------------------------------------- \
    # typos \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/typos; \
    \
    # -------------------------------------------------------- \
    # PMD (CPD) \
    # -------------------------------------------------------- \
    curl -sSfL \
      "https://github.com/pmd/pmd/releases/download/pmd_releases%2F${PMD_VERSION}/pmd-dist-${PMD_VERSION}-bin.zip" \
      -o /tmp/pmd.zip; \
    unzip /tmp/pmd.zip -d /opt; \
    ln -s "/opt/pmd-bin-${PMD_VERSION}/bin/pmd" /usr/local/bin/pmd; \
    rm -f /tmp/pmd.zip; \
    \
    # -------------------------------------------------------- \
    # Runtime user \
    # -------------------------------------------------------- \
    useradd --uid 1000 --create-home --shell /bin/bash appuser

# ============================================================
# Runtime
# ============================================================
USER appuser
WORKDIR /app
CMD ["bash"]
