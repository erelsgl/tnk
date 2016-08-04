Tanakh Navigation Kit
=====================

A set of tables and tools for navigating in the TNK (Tora Neviim Ktuvim).

# 1. Data tables
All tables are in the data/ folder, in UTF-8 encoding, tab-separated values. 
All tables are represented by 2 files: an SQL file that creates the table and a TXT file that contains the data.

* data/psuqim.* - All 23202 verses in the Tanakh, in both undotted and dotted version.
* data/sfrim.*  - Codes for the 39 books of the Tanakh.
* data/prqim.* - Codes for chapter and verse numbers in the Tanakh.

# 2. Code

## Requirements
* Apache 2+
* PHP 5.2 to 6  (does not work with PHP 7)
* MySQL 5+

## Installation

A. Clone the repository, e.g.:

	git clone https://github.com/erelsgl/tnk.git
	
B. In your Apache2 configuration, create an alias "/tnk" that points to the "web" folder. e.g.:

	sudo ln -s [full-path-to-repository]/web /var/www/tnk
	sudo ln -s [full-path-to-repository]/web /var/www/html/tnk
	sudo ln -s [full-path-to-repository]/web /opt/lampp/htdocs/tnk

C. Run the create script:

	php [full-path-to-repository]/admin/create.php

Enter your MySQL username and password, and a name of a database for creating required tables.

D. Verify that there are no errors in the output.

## Web apps

* http://localhost/tnk/findpsuq.php   - find a regular expression in the Tanakh.
* http://localhost/tnk/find.php       - find a word or a regular expression in the Tanakh, the TNK site and Google.
* http://localhost/tnk/prjot_1255.php - see the Hebrew date and the weekly Tora portion.

## Scripts
* **script/findpsuq_lib.php** - functions for searching regular expressions in the Tanakh verses. 
* **script/niqud.php** - functions for adding dots ("niqud") to Tanakh verses.

# 3. License
Both data and code is LGPL.
