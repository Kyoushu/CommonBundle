<?php

namespace Kyoushu\CommonBundle\Routing;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class DynamicRouteLoader implements LoaderInterface
{

    const TYPE = 'kyoushu_common_dynamic_routes';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var bool
     */
    protected $loaded;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->loaded = false;
    }

    /**
     * @param mixed $resource
     * @param string|null $type
     * @return RouteCollection
     * @throws \Exception
     */
    public function load($resource, $type = null)
    {
        if($this->loaded === true){
            throw new \Exception(sprintf('Do not load the "%s" loader twice', self::TYPE));
        }

        $routes = new RouteCollection();

        foreach($this->getDynamicRouteEntities() as $entity){
            $route = new Route($entity->getUrl(), $entity->getRouteParameters());
            $routes->add($entity->getRouteName(), $route);

            foreach($entity->getExtraRoutes() as $extraRouteName => $extraRoute){
                $routes->add($extraRouteName, $extraRoute);
            }
        }

        return $routes;
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return $type === self::TYPE;
    }

    /**
     * @return void
     */
    public function getResolver()
    {
    }

    /**
     * @param LoaderResolverInterface $resolver
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }

    /**
     * @return DynamicRouteInterface[]
     */
    public function getDynamicRouteEntities()
    {
        $entities = array();
        foreach($this->getDynamicRouteEntityClasses() as $class){
            $repo = $this->entityManager->getRepository($class);
            foreach($repo->findAll() as $entity){
                $entities[] = $entity;
            }
        }
        return $entities;
    }

    /**
     * @return array
     */
    public function getDynamicRouteEntityClasses()
    {
        $metadataFactory = $this->entityManager->getMetadataFactory();
        $metadata = $metadataFactory->getAllMetadata();

        $classes = array();

        foreach($metadata as $classMetadata){
            /** @var ClassMetadata $classMetadata */
            $class = $classMetadata->getName();
            if(!is_subclass_of($class, '\Kyoushu\CommonBundle\Routing\DynamicRouteInterface')){
                continue;
            }
            $classes[] = $class;
        }

        return $classes;
    }

}