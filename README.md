# Kyoushu/CommonBundle

[![Build Status](https://travis-ci.org/Kyoushu/CommonBundle.svg)](https://travis-ci.org/Kyoushu/CommonBundle)

This file has been generated automatically. It will require editing to reflect describe functionality provided by this bundle.

## Installation

Install [stof/doctrine-extensions-bundle](https://packagist.org/packages/stof/doctrine-extensions-bundle)

Add the following lines to composer.json

```json
"require": {
    "kyoushu/common-bundle": "dev-master"
}
```
    
Add the following line to app/AppKernel.php

```php
$bundles = array(
    // ...
    new Kyoushu\CommonBundle\KyoushuCommonBundle(),
    // ...
);
```
    
## Entity Traits

The bundle provides a range of traits to speed up the creation of entities

```php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kyoushu\CommonBundle\Entity\Traits as EntityTraits;

class MyEntity
{

    // Provides the property $id (auto-incrementing primary key) and related getter
    use EntityTraits\IdTrait;
    
    // Provides the properties $title and $slug and related getters/setters
    // - $slug is generated from the value of $title on persist/update
    use EntityTraits\TitleSlugTrait;
    
    // Provides the $summary property and related getters/setters
    // - Intended to be used with textarea form fields
    use EntityTraits\SummaryTrait;
    
    // Provides $created and $updated timestamp properties and related getters/setters
    // - $created is set to \DateTime('now') on persist
    // - $updated is set to \DateTime('now') on persist/update
    use EntityTraits\TimestampTrait;

}
```