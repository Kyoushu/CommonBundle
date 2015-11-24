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
     * @return string
     */
    public function getEntityClass();

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder();

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureQueryBuilder(QueryBuilder $queryBuilder);

}