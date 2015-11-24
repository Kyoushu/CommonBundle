<?php

namespace Kyoushu\CommonBundle\EntityFinder;

use Kyoushu\CommonBundle\Exception\EntityFinderException;

class EntityFinderResult implements \Iterator, \ArrayAccess
{

    /**
     * @var object[]
     */
    protected $entities;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $perPage;

    /**
     * @var array
     */
    protected $routeParameters;

    /**
     * EntityFinderResult constructor.
     * @param object[] $entities
     * @param int $total
     * @param int $page
     * @param int|null $perPage
     * @param array $routeParameters
     */
    public function __construct(array $entities, $total, $page, $perPage, array $routeParameters)
    {
        $this->entities = $entities;
        $this->total = (int)$total;
        $this->page = (int)$page;
        $this->perPage = ($perPage === null ? null : (int)$perPage);
        $this->routeParameters = $routeParameters;

    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        $perPage = $this->getPerPage();
        if($perPage === null) return 1;

        $total = $this->getTotal();

        $pages = ceil($total / $perPage);
        if($pages < 1) $pages = 1;
        return $pages;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        $page = $this->getPage();
        if($page > 1) return $page - 1;
        return null;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        $page = $this->getPage();
        $totalPages = $this->getTotalPages();
        if($page < $totalPages) return $page + 1;
        return null;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * @return object[]
     */
    public function toArray()
    {
        return $this->entities;
    }

    // ----- \Iterator implementation -----

    /**
     * @return object
     */
    public function current()
    {
        return current($this->entities);
    }

    public function next()
    {
        next($this->entities);
    }

    /**
     * @return string
     */
    public function key()
    {
        return key($this->entities);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return current($this->entities) !== false;
    }

    public function rewind()
    {
        reset($this->entities);
    }

    // ----- \ArrayAccess implementation -----

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * @param mixed $offset
     * @return object
     * @throws EntityFinderException
     */
    public function offsetGet($offset)
    {
        if(!$this->offsetExists($offset)){
            throw new EntityFinderException(sprintf(
                'Undefined offset %s',
                $offset
            ));
        }
        return $this->entities[$offset];
    }

    /**
     * @param mixed $offset
     * @param object $value
     * @throws EntityFinderException
     */
    public function offsetSet($offset, $value)
    {
        if(!is_object($value)){
            throw new EntityFinderException(sprintf(
                'Value must be an object, %s given',
                gettype($value)
            ));
        }
        $this->entities[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if(!$this->offsetExists($offset)) return;
        unset($this->entities[$offset]);
    }

}