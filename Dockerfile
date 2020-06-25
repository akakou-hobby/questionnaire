FROM php:7.0.3-cli

RUN apt update -y && apt install -y git
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin/ \
    && php -r "unlink('composer-setup.php');"

ADD ./composer.json /root
ADD ./composer.lock /root
WORKDIR /root
RUN composer.phar update
