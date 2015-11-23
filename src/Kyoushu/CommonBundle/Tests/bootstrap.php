<?php

namespace Kyoushu\CommonBundle;

use Doctrine\Common\Annotations\AnnotationRegistry;

error_reporting(E_ALL & ~E_USER_DEPRECATED); // Suppress Symfony 3.0 deprecation warnings

$loader = require(__DIR__ . '/../../../../vendor/autoload.php');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));