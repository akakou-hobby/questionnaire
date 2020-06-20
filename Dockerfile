FROM composer:latest

ADD ./composer.json /root
ADD ./composer.lock /root
WORKDIR /root
RUN composer update

