sf2-tutorial
============

Symfony2 tutorial application based on the ZF2 tutorial by Rob Allen (see https://github.com/akrabat/zf2-tutorial)

Install
-------
Composer does not work very well with Symfony, so do not use it. That's why vendor directory is included 
in the repository.

###Apache config:
Create the following virtual host entry:

    <VirtualHost *:80>
        ServerName sf2-tutorial.localhost
        DocumentRoot /path/to/sf2-tutorial/web
        <Directory /path/to/sf2-tutorial/web>
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

###Permissions
Symfony needs to write to **app/logs** and **app/cache** (easy to understand why) in 2 ways:

- from web server
- from console utility

So you need to set premissions properly on the folders **app/cache** and **app/logs**. The best way is to use ACLs.
Example for linux systems (Ubuntu):

    sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

Browse this page to check the config: http://sf2-tutorial.localhost/config.php
