SAMPLE ZF2 BASEAPP INCLUDING DOCTRINE2 AND ZDT
==============================================

Technologies
------------
- Zend Framework 2 (Skeleton App)
- Bootstrap Framework
- Doctrine 2

By default for presentation purposes the application uses sqlite as backend, you may want to change it in config/autoload/doctrine.local.php


Functionality
-------------
- User registration with account activation via token (sent by mail)
- User Login
- Contact Form
- Editing user information
- Logout
- News page (creation, modification and deletion for logged-in users)
- ACLs
- Forms, Filters, Validators
- Multilanguage
- Activity Tracking (Audit)


Installation
------------

    Run Composer
    ------------
    php composer.phar self-update
    php composer.phar install


    Create DB Schema
    ----------------
    vendor/bin/doctrine-module orm:schema-tool:create


Run the application
-------------------

    Instead of configuring Apache etc., for testing you can also use the server which is integrated in php. From the root of this project, just run:
    php -S YOUR_IP:8080 -t public/
 
    The -t parameter is important here as the document root is public/.
    Point your browser to http://YOUR_IP:8080 and enjoy!
       

Notes
-----

    If you register in the application and delivering mail does not work on your machine, you might want to activate your user manually in order to be able to login (you just need sqlite3 client installed):
    echo "update User set status=1;" | sqlite3 data/sql/example_net.db
     
    If you want to disable the bottom Zend Developer Toolbar, just go to config/application.config.php and comment out the ZendDeveloperTools line.
