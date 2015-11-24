<?php

namespace Kyoushu\CommonBundle\Routing;

use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Routing\Generator\Dumper\PhpGeneratorDumper;
use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper;
use Symfony\Component\Routing\Router;

class RouterCache
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function regenerateCache()
    {
        $this->regenerateUrlGenerator();
        $this->regenerateUrlMatcher();
    }

    private function regenerateUrlGenerator(){

        /** @var Router $router */
        $router = $this->router;

        $class = $router->getOption('generator_cache_class');
        $cache = new ConfigCache($router->getOption('cache_dir').'/'.$class.'.php', $router->getOption('debug'));

        $dumperClass = $router->getOption('generator_dumper_class');
        /** @var PhpGeneratorDumper $dumper */
        $dumper = new $dumperClass($router->getRouteCollection());

        $options = array(
            'class' => $class,
            'base_class' => $router->getOption('generator_base_class'),
        );

        $cache->write($dumper->dump($options), $router->getRouteCollection()->getResources());

    }

    private function regenerateUrlMatcher(){

        /** @var Router $router */
        $router = $this->router;

        $class = $router->getOption('matcher_cache_class');
        $cache = new ConfigCache($router->getOption('cache_dir').'/'.$class.'.php', $router->getOption('debug'));

        $dumperClass = $router->getOption('matcher_dumper_class');
        /** @var PhpMatcherDumper $dumper */
        $dumper = new $dumperClass($router->getRouteCollection());

        $options = array(
            'class' => $class,
            'base_class' => $router->getOption('matcher_base_class'),
        );

        $cache->write($dumper->dump($options), $router->getRouteCollection()->getResources());

    }

}