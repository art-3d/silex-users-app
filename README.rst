Silex Test Application
==============

Installation
------------

.. code-block:: console
    $ git clone https://github.com/art-3d/silex-users-app
    $ cd silex-users-app
    $ composer install

Then update database options inside `config/prod.php`

.. code-block:: console
    $ bin/console doctrine:database:create
    $ bin/console doctrine:schema:create
    $ bin/console fixture:load

    $ run
