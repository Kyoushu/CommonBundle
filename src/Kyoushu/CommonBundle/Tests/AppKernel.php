<?php

namespace Kyoushu\CommonBundle\Tests;

use Kyoushu\CommonBundle\KyoushuCommonBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var bool
     */
    protected $prepareDoctrineAfterBoot;

    public function __construct()
    {
        $this->uuid = uniqid();
        parent::__construct('test' . $this->uuid, true);
        $this->prepareDoctrineAfterBoot = false;
    }

    /**
     * @return boolean
     */
    public function getPrepareDoctrineAfterBoot()
    {
        return $this->prepareDoctrineAfterBoot;
    }

    /**
     * @param boolean $prepareDoctrineAfterBoot
     * @return $this
     */
    public function setPrepareDoctrineAfterBoot($prepareDoctrineAfterBoot)
    {
        $this->prepareDoctrineAfterBoot = (bool)$prepareDoctrineAfterBoot;
        return $this;
    }

    /**
     * @return Bundle[]
     */
    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new TwigBundle(),
            new DoctrineBundle(),
            new KyoushuCommonBundle(),
            new StofDoctrineExtensionsBundle()
        );
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/Resources/config/config.yml');
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sprintf('%s/temp/cache/%s', __DIR__, $this->uuid);
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sprintf('%s/temp/log/%s', __DIR__, $this->uuid);
    }

    public function boot()
    {
        parent::boot();
        if($this->prepareDoctrineAfterBoot === true){
            $this->prepareDoctrine();
        }
    }

    /**
     * Create database and update schema
     */
    protected function prepareDoctrine()
    {
        $container = $this->getContainer();

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.orm.default_entity_manager');
        $schemaManager = $entityManager->getConnection()->getSchemaManager();
        $schemaManager->createDatabase('test');

        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadatas, true);
    }

}