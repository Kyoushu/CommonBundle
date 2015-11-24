# Kyoushu/CommonBundle

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
    
### Classes

    Kyoushu
        CommonBundle
            Upload
                UploadInterface
                    getFile()
                    setFile($file)l
                    getRelTargetDir()
                    getRelPath()
                UploadHandler
                    __construct($webDir)
                    process(UploadInterface $upload)
            DynamicRoute
                DynamicRouteInterface
                    getUrl()
                    getRouteName()
                    getRouteDefaults()
                    getExtraRoutes()
                DynamicRouteLoader
                    (TBC)