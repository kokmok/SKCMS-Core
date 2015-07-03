SKCMS-Core
==========

# The Core bundle of SKCMS for Symfony2

## Installation

### Download dependecies via composer
use php composer.phar instead of composer on Windows
```
composer require FOS/user-bundle:2.0.x-dev SKCMS/core-bundle:dev-master SKCMS/front-bundle:dev-master SKCMS/tracking-bundle:dev-master SKCMS/admin-bundle dev-master
```
### Install and quick configure SKCMS
This will update and backup original versions of config.yml, security.yml and AppKernel.php
```
php app/console skcms:install
```
