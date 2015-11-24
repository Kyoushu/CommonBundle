<?php

namespace Kyoushu\CommonBundle\Tests\Entity\Traits;

use Doctrine\ORM\EntityManager;
use Kyoushu\CommonBundle\Tests\Entity\TraitsEntity;
use Kyoushu\CommonBundle\Tests\KernelTestCase;

class TraitsTest extends KernelTestCase
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

    public function testTitleSlug()
    {

        $entity = new TraitsEntity();

        $this->assertNull($entity->getTitle());
        $this->assertNull($entity->getSlug());

        $entity->setTitle('Foo Bar');

        $manager = $this->getEntityManager();

        $manager->persist($entity);
        $manager->flush();

        $this->assertEquals('Foo Bar', $entity->getTitle());
        $this->assertEquals('foo-bar', $entity->getSlug());

        $this->assertNotNull($entity->getId());
        $id = $entity->getId();

        /** @var TraitsEntity $entity */
        $entity = $manager
            ->getRepository('\Kyoushu\CommonBundle\Tests\Entity\TraitsEntity')
            ->find($id)
        ;

        $this->assertNotNull($entity);

        $this->assertEquals('Foo Bar', $entity->getTitle());
        $this->assertEquals('foo-bar', $entity->getSlug());

    }

    public function testSummary()
    {

        $entity = new TraitsEntity();

        $this->assertNull($entity->getSummary());

        $entity->setSummary('Lorem ipsum dolor sit amet.');

        $manager = $this->getEntityManager();

        $manager->persist($entity);
        $manager->flush();

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entity->getSummary());

        $this->assertNotNull($entity->getId());
        $id = $entity->getId();

        /** @var TraitsEntity $entity */
        $entity = $manager
            ->getRepository('\Kyoushu\CommonBundle\Tests\Entity\TraitsEntity')
            ->find($id)
        ;

        $this->assertNotNull($entity);

        $this->assertEquals('Lorem ipsum dolor sit amet.', $entity->getSummary());

    }

    public function testTimestamp()
    {

        $entity = new TraitsEntity();

        $this->assertNull($entity->getCreated());
        $this->assertNull($entity->getUpdated());

        $manager = $this->getEntityManager();

        $manager->persist($entity);
        $manager->flush();

        $this->assertInstanceOf('\DateTime', $entity->getCreated());
        $this->assertInstanceOf('\DateTime', $entity->getUpdated());

        $this->assertNotNull($entity->getId());
        $id = $entity->getId();

        /** @var TraitsEntity $entity */
        $entity = $manager
            ->getRepository('\Kyoushu\CommonBundle\Tests\Entity\TraitsEntity')
            ->find($id)
        ;

        $this->assertNotNull($entity);

        $this->assertInstanceOf('\DateTime', $entity->getCreated());
        $this->assertInstanceOf('\DateTime', $entity->getUpdated());

    }

}