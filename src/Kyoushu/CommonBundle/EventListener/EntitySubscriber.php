<?php

namespace Kyoushu\CommonBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Kyoushu\CommonBundle\Routing\DynamicRouteInterface;
use Kyoushu\CommonBundle\Routing\RouterCache;
use Kyoushu\CommonBundle\Upload\UploadHandler;
use Kyoushu\CommonBundle\Upload\UploadInterface;

class EntitySubscriber implements EventSubscriber
{

    /**
     * @var RouterCache
     */
    protected $routerCache;

    /**
     * @var UploadHandler
     */
    protected $uploadHandler;

    /**
     * @param RouterCache $routerCache
     * @param UploadHandler $uploadHandler
     */
    public function __construct(RouterCache $routerCache, UploadHandler $uploadHandler)
    {
        $this->routerCache = $routerCache;
        $this->uploadHandler = $uploadHandler;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postPersist',
            'postUpdate'
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $this->handleObjectPre($object);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $this->handleObjectPre($object);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $this->handleObjectPost($object);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        $this->handleObjectPost($object);
    }

    /**
     * @param object $object
     */
    protected function handleObjectPre($object)
    {
        if($object instanceof UploadInterface){
            $this->uploadHandler->process($object);
        }
    }

    /**
     * @param object $object
     */
    protected function handleObjectPost($object)
    {
        if($object instanceof DynamicRouteInterface){
            $this->routerCache->regenerateCache();
        }
    }

}