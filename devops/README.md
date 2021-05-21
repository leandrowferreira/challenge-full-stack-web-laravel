# Step-by-step to install basic infrastructure to API Backend

The backend was developed in `Laravel` and `PHP`. So, to work well, it needs a web server. This document describes how to start from fresh Ubuntu Focal Fossa (20.04) installation to get a funcional web server to application.

This document is tested in an EC2 AWS instance, using built in `ubuntu` user. If you plan to use this instructions in another environment, maybe you need to make some changes.

**Important notice!** This instructions may contains security issues, in special case of MySQL. Remember that these informations are for **staging environment** only and not applicable to final production environment.


## Table of Contents

  - [Startup adjustments](#startup-adjustments)
  - [Basic config for a simple Apache Web Server and PHP](#basic-config-for-a-simple-apache-web-server-and-php)
  - [Basic install to MySQL Server](#basic-install-to-mysql-server)
  - [NodeJS and npm](#nodejs-and-npm)
  - [Composer](#composer)
  - [File size settings](#file-size-settings)
  - [Enable Apache *rewrite* module](#enable-apache-rewrite-module)
  - [Make application tree](#make-application-tree)
  - [Configure the new site in Apache](#configure-the-new-site-in-apache)
  - [MySQL user adjustments](#mysql-user-adjustments)
  - [Clone repository](#clone-repository)
  - [Create env file](#create-env-file)
  - [Install dependencies](#install-dependencies)
  - [Migrate and seed tables and make permission adjustments](#migrate-and-seed-tables-and-make-permission-adjustments)
  - [Install SSL Certificate](#install-ssl-certificate)
  - [Restart Apache](#restart-apache)


## Startup adjustments

```bash
sudo timedatectl set-timezone America/Sao_Paulo
sudo apt update && sudo apt upgrade -y
```


## Basic config for a simple Apache Web Server and PHP

```bash
sudo apt install -y php curl libaio-dev supervisor build-essential gcc make perl dkms python3-distutils git poppler-utils nodejs libapache2-mod-php php-MySQL php-cli php-gd php-imagick php-mbstring php-zip php-curl php-xml php-dev php-pear php-tokenizer php-json zip imagemagick && sudo apt autoremove
```


## Basic install to MySQL Server

```bash
sudo apt install -y MySQL-server
```


## NodeJS and npm

```bash
curl -L https://npmjs.org/install.sh | sudo sh
sudo npm cache clean -f
sudo npm install -g n
sudo npm install -g npm
sudo n stable
```


## Composer

```bash
wget -O composer-setup.php https://getcomposer.org/installer
php composer-setup.php && rm composer-setup.php && sudo mv composer.phar /usr/local/bin/composer
```


## File size settings

```bash
sudo sed -i 's/post_max_size = .*/post_max_size = 100M/' /etc/php/7.4/apache2/php.ini
sudo sed -i 's/upload_max_filesize = .*/upload_max_filesize = 100M/' /etc/php/7.4/apache2/php.ini
```


## Enable Apache *rewrite* module

```bash
sudo a2enmod rewrite
```


## Make application tree

```bash
sudo mkdir /websystems/log/edtech.tmp.br -p
sudo mkdir /websystems/edtech.tmp.br
sudo chown ubuntu:www-data /websystems/ -R

```


## Configure the new site in Apache

```bash
sudo nano /etc/apache2/sites-available/edtech.tmp.br.conf
```

The following configuration serves a simple site with Laravel requests:

```
<VirtualHost *:80>
        ServerName edtech.tmp.br

        ServerAdmin leandroafferreira@gmail.com
        DocumentRoot /websystems/edtech.tmp.br/public

        ErrorLog /websystems/log/edtech.tmp.br/error.log
        CustomLog /websystems/log/edtech.tmp.br/access.log combined

    <Directory "/websystems/edtech.tmp.br/">
        DirectoryIndex index.php
        Options +Includes +FollowSymLinks -Indexes
        AllowOverride All
        #Order allow,deny
        Require all granted
    </Directory>
</VirtualHost>
```

After create this file, reconfigure Apache to recognize it:

```bash
sudo a2dissite 000*
sudo a2ensite edtech.tmp.br
```


## MySQL user adjustments

```bash
sudo MySQL
```

The following commands will use the MySQL console

```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH MySQL_native_password BY 'my-password';
CREATE SCHEMA `edtech.tmp.br` DEFAULT CHARACTER SET utf;
```


## Clone repository

```bash
cd /websystems
git clone https://github.com/leandrowferreira/challenge-full-stack-web-laravel.git
mv challenge-full-stack-web-laravel edtech.tmp.br
```


## Create env file

```bash
cd edtech.tmp.br
nano .env
```

Insert the basic `.env` content to make this site funcional:

```
APP_NAME="EdTech"
APP_ENV=local
APP_KEY=base64:Tm6EKfGUVoVNFiELB8aUbk8Y8SwG9S3uWz4vGw4RjJ8=
APP_DEBUG=false
APP_URL=https://edtech.tmp.br

API_VERSION=1

LOG_CHANNEL=daily
LOG_LEVEL=debug

DB_CONNECTION=MySQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edtech.tmp.br
DB_USERNAME=root
DB_PASSWORD=my-password
DB_PREFIX="ED_"

CACHE_DRIVER=file
```


## Install dependencies

```bash
composer install && composer update
```


## Migrate and seed tables and make permission adjustments

```bash
php artisan migrate --seed
sudo chmod 777 storage bootstrap/cache -R
```


## Install SSL Certificate

```bash
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot
sudo certbot --apache
```


## Restart Apache

```bash
sudo apache2 restart
```
