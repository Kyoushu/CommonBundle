<?php

namespace Kyoushu\CommonBundle\Tests\EntityFinder;

use Doctrine\ORM\QueryBuilder;
use Kyoushu\CommonBundle\EntityFinder\AbstractEntityFinder;

class TraitsEntityFinder extends AbstractEntityFinder
{

    /**
     * @var string|null
     */
    protected $title;

    public function getEntityClass()
    {
        return 'Kyoushu\CommonBundle\Tests\Entity\TraitsEntity';
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameterKeys()
    {
        return array('page', 'perPage', 'title');
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureQueryBuilder(QueryBuilder $queryBuilder)
    {
        $title = $this->getTitle();
        if($title !== null){
            $queryBuilder->andWhere('entity.title like :like_title');
            $queryBuilder->setParameter('like_title', '%' . $title . '%');
        }
    }

}