FROM php:7.1

# Add git for composer
RUN apt-get update
RUN apt-get install -y git zlib1g-dev

# Add the repository
ADD . /usr/maestro/
WORKDIR /usr/maestro/

# Install php zip extension
RUN docker-php-ext-install -j$(nproc) zip

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

# Install PHPUnit
RUN cd /tmp && curl https://phar.phpunit.de/phpunit.phar > phpunit.phar && \
    chmod +x phpunit.phar && \
    mv /tmp/phpunit.phar /usr/local/bin/phpunit

# Install reqs
RUN ./composer.phar install

# Run the unittests
CMD ./vendor/bin/phpunit
