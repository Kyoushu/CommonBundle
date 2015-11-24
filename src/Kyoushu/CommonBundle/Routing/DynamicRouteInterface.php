<?php

namespace Kyoushu\CommonBundle\Routing;

use Symfony\Component\Routing\Route;

interface DynamicRouteInterface
{

    /**
     * @return string|null
     */
    public function getUrl();

    /**
     * @return string|null
     */
    public function getRouteName();

    /**
     * @return array|null
     */
    public function getRouteParameters();

    /**
     * @return Route[]|null
     */
    public function getExtraRoutes();

}