FROM php:7.4

MAINTAINER Md Ehsanul Hoque


RUN apt-get update && apt-get update && apt-get install -y libpng-dev libjpeg-dev libcurl4-openssl-dev && rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-install mysqli opcache

# @link https://hub.docker.com/r/plab/docker-php/~/dockerfile/
RUN apt-get update && apt-get install -y \
    libzip-dev \
	bzip2 \
	libbz2-dev \
	libfreetype6-dev \
	libjpeg62-turbo-dev \
	libmcrypt-dev \
	libpng-dev \
	libghc-postgresql-libpq-dev \
    libonig-dev \
	&& docker-php-ext-install mbstring bz2 zip curl mysqli opcache pdo pdo_mysql \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/include/postgresql/ \
	&& docker-php-ext-install pgsql pdo_pgsql

RUN apt-get update \
  && apt-get install -y libmemcached11 libmemcachedutil2 build-essential libmemcached-dev libz-dev \
  && pecl install memcached \
  && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
  && apt-get remove -y build-essential libmemcached-dev libz-dev \
  && apt-get autoremove -y \
  && apt-get clean \
  && rm -rf /tmp/pear

# @link https://github.com/docker-library/php/issues/57
RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini


RUN apt-get install -y mariadb-client libxml2-dev

RUN docker-php-ext-install xml

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

VOLUME /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
    docker-php-ext-install gd
