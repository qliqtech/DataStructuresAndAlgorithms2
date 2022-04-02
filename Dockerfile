FROM composer:latest as build_stage
COPY . /src
ADD .env.example /src/.env
WORKDIR /src
RUN composer install --ignore-platform-reqs --no-scripts

FROM php:8.0-apache

LABEL maintainer="Inpath. " \
      version="1.0.1"

# ENV LIBRDKAFKA_VERSION v1.4.4  
# ENV BUILD_DEPS \ 
#         git \
#         libsasl2-dev \
#         libssl-dev \
#         python-minimal \
#         zlib1g-dev
        
RUN apt-get update \
    # && curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get install --no-install-recommends -y \ 
    mariadb-client  \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \ 
    libzip-dev \ 
    jpegoptim optipng pngquant gifsicle \
    curl \ 
    libmagickwand-dev \
    # nodejs gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgbm1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6  fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget libgbm-dev \
    # ${BUILD_DEPS} \
    # && npm install -g puppeteer --unsafe-perm=true \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install  pdo pdo_mysql opcache gd zip sockets \ 
    && docker-php-ext-configure gd \ 
    && pecl install redis \
    && pecl install imagick-beta \
    && docker-php-ext-enable redis gd imagick \
    && a2enmod rewrite negotiation  
        
# RUN cd /tmp \
#     && git clone \
#         --branch ${LIBRDKAFKA_VERSION} \
#         --depth 1 \
#         https://github.com/edenhill/librdkafka.git \
#     && cd librdkafka \
#     && ./configure \
#     && make \
#     && make install \
#     && pecl install rdkafka \
#     && docker-php-ext-enable rdkafka \
#     && rm -rf /tmp/librdkafka


#COPY --chown=www-data:www-data . /srv/app 
COPY --chown=www-data:www-data --from=build_stage /src /srv/app
COPY .devops/.docker/vhost.conf /etc/apache2/sites-available/000-default.conf 
COPY .devops/.docker/start.sh /usr/local/bin/start

# Override with custom opcache settings
COPY .devops/.docker/custom.ini $PHP_INI_DIR/conf.d/

RUN chmod u+x /usr/local/bin/start


WORKDIR /srv/app
# RUN php artisan key:generate
# # RUN php artisan config:cache
# RUN php artisan route:cache
# RUN php artisan event:cache
# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

ADD .devops/.docker/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

CMD ["/usr/local/bin/start"]
    
