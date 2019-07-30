# @description php image base on the debian 9.x
#
#                       Some Information
# ------------------------------------------------------------------------------------
# @link https://hub.docker.com/_/debian/      alpine image
# @link https://hub.docker.com/_/php/         php image
# @link https://github.com/docker-library/php php dockerfiles
# @see https://github.com/docker-library/php/tree/master/7.2/stretch/cli/Dockerfile
# ------------------------------------------------------------------------------------
#
FROM php:7.2

LABEL maintainer="wayne<waynechen@hinabian.com>" version="2.0"

ARG timezone

ENV PHPREDIS_VERSION=4.3.0 \
    MSGPACK_VERSION=2.0.3 \
    SWOOLE_VERSION=4.3.5

#ADD . /var/www/one-app

# Libs -y --no-install-recommends
RUN apt-get update \
    && apt-get install -y \
        curl wget git zip unzip less vim openssl iproute2 iputils-ping net-tools procps \
        libz-dev libssl-dev libnghttp2-dev libpcre3-dev libjpeg-dev libpng-dev libfreetype6-dev \
# Install PHP extensions
    && docker-php-ext-install \
       bcmath gd pdo_mysql mbstring sockets zip sysvmsg sysvsem sysvshm \
# Install redis extension
    && wget http://pecl.php.net/get/redis-${PHPREDIS_VERSION}.tgz -O /tmp/redis.tar.tgz \
    && pecl install /tmp/redis.tar.tgz \
    && rm -rf /tmp/redis.tar.tgz \
    && docker-php-ext-enable redis \
# Install msgpack extension
    && wget http://pecl.php.net/get/msgpack-${MSGPACK_VERSION}.tgz -O /tmp/msgpack.tar.tgz \
    && pecl install /tmp/msgpack.tar.tgz \
    && rm -rf /tmp/msgpack.tar.tgz \
    && docker-php-ext-enable msgpack \
# Install swoole extension
    && wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
        cd swoole \
        && phpize \
        && ./configure --enable-mysqlnd --enable-sockets --enable-openssl --enable-http2 \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r swoole \
    && docker-php-ext-enable swoole \
# Clear dev deps
    && apt-get clean \
    && apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
# Timezone
    && cp /usr/share/zoneinfo/${timezone} /etc/localtime \
    && echo "${timezone}" > /etc/timezone \
    && echo "[Date]\ndate.timezone=${timezone}" > /usr/local/etc/php/conf.d/timezone.ini

WORKDIR /var/www/one-app
EXPOSE 19101 19102 19103

CMD ["php", "/var/www/one-app/App/swoole.php"]
