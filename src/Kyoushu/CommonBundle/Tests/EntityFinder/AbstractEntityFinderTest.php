<?php

namespace Kyoushu\CommonBundle\Tests\EntityFinder;

use Doctrine\ORM\EntityManager;
use Kyoushu\CommonBundle\Tests\Entity\TraitsEntity;
use Kyoushu\CommonBundle\Tests\KernelTestCase;

class AbstractEntityFinderTest extends KernelTestCase
{

    protected function setUp()
    {
        self::bootKernel(array(
            'prepare_doctrine' => true
        ));

        $manager = $this->getEntityManager();

        for($i = 1; $i <= 50; $i++){
            $entity = new TraitsEntity();
            $entity->setTitle(sprintf('Title %s', $i));
            $entity->setSummary(sprintf('Summary %s', $i));
            $manager->persist($entity);
        }

        $manager->flush();

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

    public function testGetResultFiltered()
    {
        $finder = new TraitsEntityFinder($this->getEntityManager());
        $finder->setPerPage(null);
        $finder->setTitle('23');

        $result = $finder->getResult();

        $this->assertEquals(1, $result->getTotal());

        /** @var TraitsEntity $entity */
        $entity = $result[0];
        $this->assertEquals('Title 23', $entity->getTitle());
    }

    public function testGetResultUnpaginated()
    {

        $finder = new TraitsEntityFinder($this->getEntityManager());
        $finder->setPerPage(null);

        $this->assertEquals(50, $finder->getTotal());

        $result = $finder->getResult();

        $this->assertEquals(1, $result->getTotalPages());
        $this->assertEquals(1, $result->getPage());
        $this->assertNull($result->getPerPage());

    }

    public function testGetResultPaginated()
    {

        $finder = new TraitsEntityFinder($this->getEntityManager());
        $finder->setPerPage(5);
        $finder->setPage(2);

        $this->assertEquals(50, $finder->getTotal());

        $result = $finder->getResult();

        $this->assertEquals(10, $result->getTotalPages());
        $this->assertEquals(2, $result->getPage());
        $this->assertEquals(5, $result->getPerPage());

        $this->assertEquals(1, $result->getPrevPage());
        $this->assertEquals(3, $result->getNextPage());

        foreach($result as $key => $entity){
            /** @var TraitsEntity $entity */
            $id = $key + 6;
            $this->assertEquals($id, $entity->getId());
            $this->assertEquals(sprintf('Title %s', $id), $entity->getTitle());
        }

        $routeParameters = $result->getRouteParameters();

        $this->assertArrayHasKey('page', $routeParameters);
        $this->assertEquals(2, $routeParameters['page']);

        $this->assertArrayHasKey('perPage', $routeParameters);
        $this->assertEquals(5, $routeParameters['perPage']);

    }

}