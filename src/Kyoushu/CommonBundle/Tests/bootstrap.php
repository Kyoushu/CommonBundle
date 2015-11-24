<?php

namespace Kyoushu\CommonBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Filesystem\Filesystem;

error_reporting(E_ALL & ~E_USER_DEPRECATED); // Suppress Symfony 3.0 deprecation warnings

$loader = require(__DIR__ . '/../../../../vendor/autoload.php');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

register_shutdown_function(function(){

    $fs = new Filesystem();

    $tempDir = sprintf('%s/temp', __DIR__);
    if($fs->exists($tempDir)) $fs->remove($tempDir);

});