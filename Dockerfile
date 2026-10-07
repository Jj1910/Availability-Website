FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

# Hardening: never leak error details/stack traces to the browser
RUN printf 'display_errors = Off\nlog_errors = On\nexpose_php = Off\n' \
    > /usr/local/etc/php/conf.d/zz-security.ini
