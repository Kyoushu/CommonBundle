<?php

namespace Kyoushu\CommonBundle\EntityFinder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractEntityFinder implements EntityFinderInterface
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $perPage;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setPage(1);
        $this->setPerPage(self::PER_PAGE_DEFAULT);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = (int)$page;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = ($perPage === null ? null : (int)$perPage);
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameterKeys()
    {
        return array('perPage', 'page');
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $parameters = array();
        foreach($this->getRouteParameterKeys() as $key){
            $value = $propertyAccessor->getValue($this, $key);
            $parameters[$key] = $value;
        }
        return $parameters;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return (int)$this
            ->createQueryBuilder()
            ->select(sprintf('COUNT(DISTINCT %s.id)', self::ROOT_ENTITY_ALIAS))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getResult()
    {

        $perPage = $this->getPerPage();
        $page = $this->getPage();

        $queryBuilder = $this->createQueryBuilder();

        try
        {
            if ($perPage !== null) {
                $queryBuilder->setMaxResults($perPage);
                $queryBuilder->setFirstResult($perPage * ($page - 1));
                $query = $queryBuilder->getQuery();
                $paginator = new Paginator($query, true);
                $entities = $paginator->getIterator()->getArrayCopy();
            } else {
                $query = $queryBuilder->getQuery();
                $entities = $query->getResult();
            }

            $total = $this->getTotal();
        }
        catch(NoResultException $e)
        {
            $entities = array();
            $total = 0;
        }

        $routeParameters = $this->getRouteParameters();

        return new EntityFinderResult($entities, $total, $page, $perPage, $routeParameters);

    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $entityClass = $this->getEntityClass();

        $queryBuilder = $this->getEntityManager()
            ->getRepository($entityClass)
            ->createQueryBuilder(self::ROOT_ENTITY_ALIAS)
        ;

        $this->configureQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
    }

}