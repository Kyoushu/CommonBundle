# Kyoushu/CommonBundle

[![Build Status](https://travis-ci.org/Kyoushu/CommonBundle.svg)](https://travis-ci.org/Kyoushu/CommonBundle)

This file has been generated automatically. It will require editing to reflect describe functionality provided by this bundle.

## Installation

Add the following lines to composer.json

    "require": {
        "kyoushu/common-bundle": "dev-master"
    }
    
Add the following line to app/AppKernel.php
    
    $bundles = array(
        // ...
        new Kyoushu\CommonBundle\KyoushuCommonBundle(),
        // ...
    );
    
## Todo

### Services

    Upload handler
    Dynamic route loader
    
### Event Listeners/Subscribers

    Entity
        Upload handler
    
### Classes

    Kyoushu
        CommonBundle
            DynamicRoute
                DynamicRouteInterface
                    getUrl()
                    getRouteName()
                    getRouteDefaults()
                    getExtraRoutes()
                DynamicRouteLoader
                    (TBC)