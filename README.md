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

### Bundle Dependencies

    stof/doctrine-extensions-bundle ~1.2
    
### Classes

    Kyoushu
        CommonBundle
            Entity
                Trait
                    TimestampTrait
                        \DateTime $created
                        \DateTime $updated
                    TitleTrait
                        string $title
                    TitleSlugTrait
                        string $title
                        string $slug
                    SummaryTrait
                        string $summary
            Upload
                UploadInterface
                    getFile()
                    setFile($file)
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