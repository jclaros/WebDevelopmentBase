# WebDevelopmentBase
This is a project created to show the basic configuration for rest handling with the latest bundles for symfony2


___first clone the projects___

Steps to get this running
# requisites:
postgres sql server, with a user with appropiate roles named: "admin" with password: "admin"
apache2 configured, listening on a port or virtualhost

#steps
1.- get composer:
```bash
$ curl -s http://getcomposer.org/installer | php
```
2.- composer install dependencies
```bash
$ php composer.phar install
```
3.- check symfony requirements:
```bash
$ php app/check.php
```
4.- database creation and populate schema:
```bash
$ php app/console doctrine:schema:update --force
```
5.- load base data:
```bash
$ php app/console doctrine:fixtures:load
```
6.- cache clear
```bash
$ sudo rm -Rf app/cache/* && sudo chmod -Rf 777 app/cache app/logs
```
7.- open your code on the browser with the location of the virtualhost or port and enjoy