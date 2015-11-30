<?php

namespace Kyoushu\CommonBundle\EntityFinder;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

interface EntityFinderInterface
{

    const ROUTE_PARAMETER_NULL_PLACEHOLDER = '-';
    const ROOT_ENTITY_ALIAS = 'entity';
    const PER_PAGE_DEFAULT = 20;

    /**
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * @return EntityRepository
     */
    public function getRepository();

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param int $page
     * @return $this
     */
    public function setPage($page);

    /**
     * @return int|null
     */
    public function getPerPage();

    /**
     * @param int $perPage
     * @return $this
     */
    public function setPerPage($perPage);

    /**
     * @return array
     */
    public function getRouteParameterKeys();

    /**
     * @return array
     */
    public function getRouteParameters();

    /**
     * @param array $parameters
     * @return $this
     */
    public function setRouteParameters(array $parameters);

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return EntityFinderResult
     */
    public function getResult();

    /**
     * Fully namespaced class for the entity
     *
     * @return string
     */
    public function getEntityClass();

    /**
     * Creates a query builder used to fetch entities
     *
     * @return QueryBuilder
     */
    public function createResultQueryBuilder();

    /**
     * Creates a query builder used to perform a total count
     *
     * @return QueryBuilder
     */
    public function createTotalQueryBuilder();

    /**
     * Configures a query builder to fetch entities. The query builder should not be paginated with this method.
     *
     * @param QueryBuilder $queryBuilder
     */
    public function configureResultQueryBuilder(QueryBuilder $queryBuilder);

    /**
     * Configures a query builder to perform a total count
     *
     * @param QueryBuilder $queryBuilder
     */
    public function configureTotalQueryBuilder(QueryBuilder $queryBuilder);

}