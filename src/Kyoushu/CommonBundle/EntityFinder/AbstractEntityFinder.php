<?php

namespace Kyoushu\CommonBundle\EntityFinder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kyoushu\CommonBundle\Exception\EntityFinderException;
use Kyoushu\CommonBundle\Meta\PropertyTypeDetector;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

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
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setPage(1);
        $this->setPerPage(self::PER_PAGE_DEFAULT);
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
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
        $parameters = array();
        foreach($this->getRouteParameterKeys() as $key){
            $value = $this->propertyAccessor->getValue($this, $key);
            if($value === 'NULL'){
                $value = self::ROUTE_PARAMETER_NULL_PLACEHOLDER;
            }
            elseif(is_object($value)){
                if($value instanceof \DateTime){
                    $value = $value->format('c');
                }
            }

            $parameters[$key] = $value;
        }
        return $parameters;
    }

    public function setRouteParameters(array $parameters)
    {

        $keys = $this->getRouteParameterKeys();

        foreach(array_keys($parameters) as $key){
            if(!in_array($key, $keys)){
                throw new EntityFinderException(sprintf(
                    '%s is not a valid route parameter key for %s',
                    $key,
                    get_class($this)
                ));
            }
        }

        foreach($this->getRouteParameterKeys() as $key){
            if(!isset($parameters[$key])) continue;
            $value = $parameters[$key];

            if($value === self::ROUTE_PARAMETER_NULL_PLACEHOLDER){
                $value = null;
            }

            $detectedType = PropertyTypeDetector::detect($this, $key);
            if($detectedType === '\DateTime'){
                $value = new \DateTime($value);
            }

            $this->setPropertyValue($key, $value);

        }
        return $this;
    }

    /**
     * @param string $propertyName
     * @return mixed
     */
    protected function getPropertyValue($propertyName)
    {
        return $this->propertyAccessor->getValue($this, $propertyName);
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     * @return $this
     */
    protected function setPropertyValue($propertyName, $value)
    {
        $this->propertyAccessor->setValue($this, $propertyName, $value);
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return (int)$this
            ->createTotalQueryBuilder()
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function getResult()
    {

        $perPage = $this->getPerPage();
        $page = $this->getPage();

        $queryBuilder = $this->createResultQueryBuilder();

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
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->getEntityManager()
            ->getRepository( $this->getEntityClass() )
        ;
    }

    /**
     * @return QueryBuilder
     */
    public function createResultQueryBuilder()
    {
        $queryBuilder = $this
            ->getRepository()
            ->createQueryBuilder(self::ROOT_ENTITY_ALIAS)
        ;

        $this->configureResultQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function createTotalQueryBuilder()
    {
        $queryBuilder = $this
            ->getRepository()
            ->createQueryBuilder(self::ROOT_ENTITY_ALIAS)
        ;

        $this->configureTotalQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureResultQueryBuilder(QueryBuilder $queryBuilder)
    {
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureTotalQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->configureResultQueryBuilder($queryBuilder);
        $queryBuilder->select(sprintf('COUNT(DISTINCT %s.id)', self::ROOT_ENTITY_ALIAS));
    }

}