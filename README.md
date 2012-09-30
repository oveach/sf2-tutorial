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
Don't forget to let the folders app/cache and app/logs writable by your webserver:

    chgrp -R www-data app/cache/
    chgrp -R www-data app/logs/

Browse this page to check the config: http://sf2-tutorial.localhost/config.php
