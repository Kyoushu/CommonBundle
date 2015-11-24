<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kyoushu\CommonBundle\Entity\Traits\IdTrait;
use Kyoushu\CommonBundle\Routing\DynamicRouteInterface;
use Symfony\Component\Routing\Route;

/**
 * @ORM\Entity()
 */
class DynamicRouteEntity implements DynamicRouteInterface
{

    use IdTrait;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return sprintf('dynamic_route_%s', $this->getId());
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return array(
            '_controller' => 'AcmeDemoBundle:Default:index'
        );
    }

    /**
     * @return array
     */
    public function getExtraRoutes()
    {
        return array(
            sprintf('dynamic_route_%s_extra', $this->getId()) => new Route(
                sprintf('%s/extra', $this->getUrl()),
                array(
                    '_controller' => 'AcmeDemoBundle:Default:extra'
                )
            )
        );
    }


}