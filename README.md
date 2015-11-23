# Kyoushu/CommonBundle

This file has been generated automatically. It will require editing to reflect describe functionality provided by this bundle.

## Installation

Add the following lines to composer.json

    "repositories": [
        {
            "type": "vcs",
            "url": "ssh://git@scm.accordgroup.co.uk/opt/aw_git/aw_common_bundle.git"
        }
    ],
    "require": {
        "accord/common-bundle": "dev-master"
    }
    
Add the following line to app/AppKernel.php
    
    $bundles = array(
        // ...
        new Kyoushu\CommonBundle\KyoushuCommonBundle(),
        // ...
    );
    
Add the following lines to app/config/routing.yml

    # ...
    
    kyoushu_common:
        resource: "@KyoushuCommonBundle/Resources/config/routing.yml"
        prefix: /
    
    # ...