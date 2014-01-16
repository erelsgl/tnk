Tanakh Navigation Kit
=====================

A set of tools for navigating in the TNK (Tora Neviim Ktuvim)

## Requirements
* Apache 2+
* PHP 5.2+
* MySQL 5+

## Installation
* Clone the repository.
* In your Apache2 configuration, create an alias "/tnk" that points to the "web" folder. This can be done, for example, with the following Linux command:
	sudo ln -s <full-path-to-repository>/web /var/www/tnk
* Run the create script:
	php <full-path-to-repository>/admin/create.php
* Enter your MySQL username and password, and a name of a database for creating required tables.
* Verify that there are no errors in the output.

## Use
* Go to http://localhost/tnk/findpsuq.php

## Code
* **script/findpsuq_lib.php** - functions for searching regular expressions in the Tanakh verses. 
* **script/niqud.php** - functions for adding dots ("niqud") to Tanakh verses.

## License
LGPL
