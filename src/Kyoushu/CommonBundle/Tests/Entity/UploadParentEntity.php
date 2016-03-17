<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kyoushu\CommonBundle\Entity\Traits\IdTrait;

/**
 * @ORM\Entity()
 */
class UploadParentEntity
{

    use IdTrait;

    /**
     * @var UploadChildEntity|null
     * @ORM\OneToOne(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity", mappedBy="parent", cascade={"all"})
     */
    protected $child;

    /**
     * @var UploadChildEntity[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity", inversedBy="parents", cascade={"all"})
     */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return UploadChildEntity|null
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param UploadChildEntity|null $child
     * @return $this
     */
    public function setChild(UploadChildEntity $child = null)
    {
        if($child) $child->setParent($this);
        $this->child = $child;
        return $this;
    }

    /**
     * @return UploadChildEntity[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param UploadChildEntity $child
     * @return $this
     */
    public function addChild(UploadChildEntity $child)
    {
        $this->children->add($child);
        return $this;
    }

    /**
     * @param UploadChildEntity $child
     */
    public function removeChild(UploadChildEntity $child)
    {
        $this->children->removeElement($child);
    }

}