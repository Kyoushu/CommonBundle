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
     * @ORM\OneToOne(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity", inversedBy="parent", cascade={"all"})
     */
    protected $oneToOneChild;

    /**
     * @var UploadChildEntity[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity", inversedBy="parents", cascade={"all"})
     */
    protected $manyToManyChildren;

    /**
     * @var UploadChildEntity[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity", mappedBy="manyToOneParent", cascade={"all"})
     */
    protected $oneToManyChildren;

    public function __construct()
    {
        $this->manyToManyChildren = new ArrayCollection();
        $this->oneToManyChildren = new ArrayCollection();
    }

    /**
     * @return UploadChildEntity|null
     */
    public function getOneToOneChild()
    {
        return $this->oneToOneChild;
    }

    /**
     * @param UploadChildEntity|null $child
     * @return $this
     */
    public function setOneToOneChild(UploadChildEntity $child = null)
    {
        $this->oneToOneChild = $child;
        return $this;
    }

    /**
     * @return ArrayCollection|UploadChildEntity[]
     */
    public function getManyToManyChildren()
    {
        return $this->manyToManyChildren;
    }

    /**
     * @param UploadChildEntity $child
     * @return $this
     */
    public function addManyToManyChild(UploadChildEntity $child)
    {
        $this->manyToManyChildren->add($child);
        return $this;
    }

    /**
     * @param UploadChildEntity $child
     */
    public function removeManyToManyChild(UploadChildEntity $child)
    {
        $this->manyToManyChildren->removeElement($child);
    }

    /**
     * @return ArrayCollection|UploadChildEntity[]
     */
    public function getOneToManyChildren()
    {
        return $this->oneToManyChildren;
    }

    /**
     * @param UploadChildEntity $child
     * @return $this
     */
    public function addOneToManyChild(UploadChildEntity $child)
    {
        $child->setManyToOneParent($this);
        $this->oneToManyChildren->add($child);
        return $this;
    }

    /**
     * @param UploadChildEntity $child
     */
    public function removeOneToManyChild(UploadChildEntity $child)
    {
        $this->oneToManyChildren->removeElement($child);
        $child->setManyToOneParent(null);
    }

}