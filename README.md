sf2-tutorial
============

Symfony2 tutorial application based on the ZF2 tutorial by Rob Allen (see https://github.com/akrabat/zf2-tutorial)

Install
-------
> Note: All commands are given for linux, specifically tested on Ubuntu.

Composer does not work very well with Symfony, so do not use it. That's why vendor directory is included 
in the repository.

###Apache config:
Create the following virtual host entry (place it into a file named `sf2tutorial.conf` in `/etc/apache2/sites-available`):

    <VirtualHost *:80>
        ServerName sf2-tutorial.localhost
        DocumentRoot /path/to/sf2-tutorial/web
        <Directory /path/to/sf2-tutorial/web>
            AllowOverride All
            
            # apache 2.2 style
            #Order allow,deny
            #Allow from all
            
            # apache 2.4 style
            Require all granted
        </Directory>
    </VirtualHost>

Of course you need to change `/path/to` with the real path of your project!
Then enable the vhost with the following commands:

    sudo a2ensite sf2tutorial
    sudo service apache2 reload

Don't forget to add `sf2-tutorial.localhost` in your `/etc/hosts` file.

###Permissions
Symfony needs to write to **app/logs** and **app/cache** (easy to understand why) in 2 ways:

- from web server
- from console utility

So you need to set premissions properly on the folders **app/cache** and **app/logs**. The best way is to use ACLs.
Example for linux systems (Ubuntu):

    sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs

Browse this page to check the config: http://sf2-tutorial.localhost/config.php

###Database
Create a mysql database named **sf2tutorial** and add a user **sf2tuto** with password **sf2tuto**.
Then run the following command to create the schema:

    php app/console doctrine:schema:update --force
    
###Test your app
Browse to http://sf2-tutorial.localhost/app_dev.php/album
