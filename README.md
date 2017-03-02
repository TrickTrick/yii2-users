## Installation

- Open terminal
- php composer.phar global require "fxp/composer-asset-plugin:^1.2.0" 
- php composer.phar install
- yii migrate
- yii migrate --migrationPath=@yii/rbac/migrations
- http://yii2.localhost/site/init - yii2.localhost = (your localsite url)


VirtualHost
------------
    <VirtualHost *:80>
     DocumentRoot "C:\projects\yii2"
     ServerName yii2.localhost
      <Directory "C:\projects\yii2">
        AllowOverride All
        Order allow,deny
        Allow from all
        Require all granted
      </Directory>
    </VirtualHost>

Hosts
------------

    127.0.0.1 yii2.localhost
