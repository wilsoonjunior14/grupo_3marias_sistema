FROM php:8-apache

RUN apt-get update -y

RUN apt-get install git curl zip -y

RUN apt-get install libpq-dev -y

RUN docker-php-ext-install pdo pgsql pdo_mysql pdo_pgsql

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
install-php-extensions gd xdebug

# Downloading and Installing the AWS Cli
RUN curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "/tmp/awscliv2.zip"
RUN unzip -u /tmp/awscliv2.zip
RUN ./aws/install

# Adding configuration for Uploads
#upload
RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini
