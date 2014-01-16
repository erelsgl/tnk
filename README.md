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
* Make sure there are no errors.

## Use
* Go to http://localhost/tnk/findpsuq.php
