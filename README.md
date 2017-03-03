## Installation

- Open terminal
- php composer.phar global require "fxp/composer-asset-plugin:^1.2.0" 
- php composer.phar install
- yii migrate
- http://yii2.localhost/site/init - yii2.localhost = (your localsite url)


Apache config 
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
    
    
REST
---------------
In this controller Available only Create action
    
    curl -i -H "Accept:application/json" -H "Content-Type:application/json" -XPOST "http://yii2.localhost/users" -d '{"username":"example", "email":"test@example.com", "password":"qweqwe", "country":"USA", "birthday":"2017-02-28",  "role": "10"}'

 User Roles
 --------
- 0 => User (Access only to Dashboard)
- 5 => Manager (Access to Dashboard and Admin Panel only View action)
- 10 => Administrator (Full Access)

---
TEST
---
- vendor\bin\codecept build
- vendor\bin\codecept run
 
