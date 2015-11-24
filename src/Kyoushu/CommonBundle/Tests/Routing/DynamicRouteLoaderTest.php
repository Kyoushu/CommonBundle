<?php

namespace Kyoushu\CommonBundle\Tests\Routing;

use Doctrine\ORM\EntityManager;
use Kyoushu\CommonBundle\Routing\RouterCache;
use Kyoushu\CommonBundle\Tests\Entity\DynamicRouteEntity;
use Kyoushu\CommonBundle\Tests\KernelTestCase;
use Symfony\Component\Routing\Router;

class DynamicRouteLoaderTest extends KernelTestCase
{

    protected function setUp()
    {
        self::bootKernel(array(
            'prepare_doctrine' => true
        ));
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return self::$kernel
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
        ;
    }

    /**
     * @return RouterCache
     */
    protected function getRouterCache()
    {
        return self::$kernel
            ->getContainer()
            ->get('kyoushu_common.routing.router_cache')
        ;
    }

    /**
     * @return Router
     */
    protected function getRouter()
    {
        return self::$kernel
            ->getContainer()
            ->get('router')
        ;
    }

    public function testLoad()
    {

        $entity = new DynamicRouteEntity();
        $entity->setUrl('/foo/bar');

        $manager = $this->getEntityManager();
        $manager->persist($entity);
        $manager->flush($entity);

        /** @var array $match */
        $match = $this->getRouter()->match('/foo/bar');

        $this->assertEquals('AcmeDemoBundle:Default:index', $match['_controller']);
        $this->assertEquals('dynamic_route_1', $match['_route']);

        /** @var array $match */
        $match = $this->getRouter()->match('/foo/bar/extra');

        $this->assertEquals('AcmeDemoBundle:Default:extra', $match['_controller']);
        $this->assertEquals('dynamic_route_1_extra', $match['_route']);

    }

}