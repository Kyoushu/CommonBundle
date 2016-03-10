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

    public function testRouteParameters()
    {

        $finder = new TraitsEntityFinder($this->getEntityManager());

        $finder->setPerPage(15);
        $finder->setPage(2);
        $finder->setTitle('foo');
        $finder->setCreatedAfter(new \DateTime('2015-01-01 00:00'));

        $this->assertEquals(
            array(
                'page' => 2,
                'perPage' => 15,
                'title' => 'foo',
                'createdAfter' => '2015-01-01T00:00:00+00:00'
            ),
            $finder->getRouteParameters()
        );

        $finder->setRouteParameters(array(
            'page' => 3,
            'perPage' => '-',
            'title' => 'bar',
            'createdAfter' => '3rd Feb 2016 00:00'
        ));

        $this->assertEquals(3, $finder->getPage());
        $this->assertNull($finder->getPerPage());
        $this->assertEquals('bar', $finder->getTitle());
        $this->assertInstanceOf('\DateTime', $finder->getCreatedAfter());
        $this->assertEquals('2016-02-03 00:00', $finder->getCreatedAfter()->format('Y-m-d H:i'));

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