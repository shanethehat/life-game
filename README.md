Game of Life
============

Simple Life simulator based on the Conway rules (http://en.wikipedia.org/wiki/Conway's_Game_of_Life).

Composer
--------

Autoloading is performed with the Composer autoloader. If you do not have composer installed in a common location you can install it into the root directory like this:

    curl -s https://getcomposer.org/installer | php

Once installed, run composer install to set up autoloading.

Tests
-----

Tests are run from the tests folder using `phpunit`. Before running the tests, the composer dev dependancies must be pulled in.
