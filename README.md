sf2-tutorial
============

Symfony2 tutorial application based on the ZF2 tutorial by Rob Allen (see https://github.com/akrabat/zf2-tutorial)

Install
-------
After cloning project from git, install dependencies (called "vendors") with the following command line:
php composer.phar bin/vendors install

Apache config:
Create the following virtual host entry:
<VirtualHost *:80>
    ServerName sf2-tutorial.localhost
    DocumentRoot "C:\projets\sf2-tutorial\web"
    <Directory "C:\projets\sf2-tutorial\web">
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>

Keep the dependencies up to date
--------------------------------
To update the vendors :
php composer.phar bin/vendors update
