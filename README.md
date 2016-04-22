SKCMS-Core
==========

This package is currently under development, more documentation and features will come soon.


# The Core bundle of SKCMS for Symfony2

## Installation

### Download dependecies via composer
use php composer.phar instead of composer on Windows
```
composer require FOS/user-bundle:2.0.x-dev SKCMS/core-bundle:dev-master SKCMS/front-bundle:dev-master SKCMS/tracking-bundle:dev-master SKCMS/admin-bundle dev-master
```
### Install and quick configure SKCMS
Add this line to AppKernel.php
```
 new \SKCMS\CoreBundle\SKCMSCoreBundle(),
```
This will update and backup original versions of config.yml, security.yml,routing.yml and AppKernel.php
```
php app/console skcms:install
```


## Usage
###Create SKCMS compatible entities 
This only works with annotation format.
```
php app/console skcms:generate:entity
```

###Create SKCMS compatible entity form type 

```
php app/console skcms:generate:form BundleName:Entity
```

