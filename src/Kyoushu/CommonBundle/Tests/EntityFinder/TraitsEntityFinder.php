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

    /**
     * @var \DateTime|null
     */
    protected $createdAfter;

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
     * @return \DateTime|null
     */
    public function getCreatedAfter()
    {
        return $this->createdAfter;
    }

    /**
     * @param \DateTime|null $createdAfter
     * @return $this
     */
    public function setCreatedAfter($createdAfter)
    {
        $this->createdAfter = $createdAfter;
        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParameterKeys()
    {
        return array('page', 'perPage', 'title', 'createdAfter');
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function configureResultQueryBuilder(QueryBuilder $queryBuilder)
    {
        $title = $this->getTitle();
        if($title !== null){
            $queryBuilder->andWhere('entity.title LIKE :like_title');
            $queryBuilder->setParameter('like_title', '%' . $title . '%');
        }

        $createdAfter = $this->getCreatedAfter();
        if($createdAfter !== null){
            $queryBuilder->andWhere('entity.created >= :created_after');
            $queryBuilder->setParameter('created_after', $createdAfter);
        }
    }

}