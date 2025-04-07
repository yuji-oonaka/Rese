FROM php:8.3-fpm

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    unzip \
    curl \
    gnupg \
    libmagickwand-dev \
    git \
    make \
    autoconf \
    --no-install-recommends \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath \
    && git clone https://github.com/Imagick/imagick.git /tmp/imagick \
    && cd /tmp/imagick \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && docker-php-ext-enable imagick \
    && apt-get purge -y libmagickwand-dev git make autoconf \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/imagick

# Composerのインストール（バージョン指定）
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.5.8

# 非ルートユーザーの作成
RUN groupadd -g 1000 appuser && useradd -u 1000 -g appuser -m appuser

# アプリケーションディレクトリの作成と権限設定
RUN mkdir -p /var/www && chown appuser:appuser /var/www

WORKDIR /var/www

# 非ルートユーザーに切り替え
USER appuser

# PHP設定の最適化（オプション）
ENV PHP_MEMORY_LIMIT=256M
ENV PHP_UPLOAD_MAX_FILESIZE=10M
ENV PHP_POST_MAX_SIZE=10M
