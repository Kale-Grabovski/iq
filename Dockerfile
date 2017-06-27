# Ubuntu
FROM ubuntu:latest

# Set the working directory to /app
WORKDIR /app

# Copy the current directory contents into the container at /app
ADD . /app

# Some software
RUN apt-get update && apt-get install software-properties-common python-software-properties -y

# PHP and Postgres
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
RUN apt-get update && apt-get install -y \
    curl \
	php7.1 \
	php7.1-cli \
	php7.1-dom \
	php7.1-fpm \
	php7.1-mbstring \
	php7.1-mcrypt \
	php7.1-pgsql \
	php7.1-zip \
	postgresql \
	vim

# Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN composer install

# Make port 8000 available to the world outside this container
EXPOSE 8000

ENTRYPOINT service postgresql start && /bin/bash
