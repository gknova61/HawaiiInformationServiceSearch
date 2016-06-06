# HawaiiInformationServiceSearch
An unofficial API for the HawaiiInformationService Real Estate Listings (a.k.a. AlohaLiving)

External Libraries/Services Used:

- [ChromePHP](https://github.com/ccampbell/chromephp) (debugging)
- [Import.io](https://www.import.io) (Web Scraper for data from HawaiiInformation)
- [Orchestrate.io](https://orchestrate.io) (Part of the database backend)
- [Orchestrate-php by socialnick](https://github.com/SocalNick/orchestrate-php-client) (PHP client library for Orchestrate.io)
- [PHPUtils](https://github.com/gknova61/PHPUtils) (all around personal library)

##Getting Started
1. Signup for an account on [Orchestrate.io](https://orchestrate.io), create an app, and obtain an API key.
2. Signup for an account on [Import.io](https://www.import.io), and obtain an API key.
3. Refer to [`/config/config.php`](https://github.com/gknova61/HawaiiInformationServiceSearch/blob/master/config/config.php) to input your API keys.
4. Refer to [`/config/MySQLDBInfo.php`](https://github.com/gknova61/HawaiiInformationServiceSearch/blob/master/config/MySQLDBInfo.php) to input information to a MySQL Database (and MySQL user) for the program to use.
6. Refer to the [`/mysql`](https://github.com/gknova61/HawaiiInformationServiceSearch/blob/master/mysql) folder for table setups within a schema. Run the [`/mysql/create_schema.sql`](https://github.com/gknova61/HawaiiInformationServiceSearch/blob/master/mysql/create_schema.sql) file, then the remaining ones to create the necessary tables

The MySQL user only needs SELECT, INSERT, and DELETE privileges on the realEstateApp schema.

**Disclaimer for Orchestrate.io and Import.io:** Depending on how many listings are in the database for AlohaLiving, this can eat up a lot of your limited API queries quickly.
