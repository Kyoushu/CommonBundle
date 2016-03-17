<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Kyoushu\CommonBundle\Entity\Traits\IdTrait;
use Kyoushu\CommonBundle\Upload\UploadInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity()
 */
class UploadChildEntity implements UploadInterface
{

    use IdTrait;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $relPath;

    /**
     * @var File|null
     */
    protected $file;

    /**
     * @var UploadParentEntity|null
     * @ORM\OneToOne(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadParentEntity", mappedBy="oneToOneChild")
     */
    protected $oneToOneParent;

    /**
     * @var UploadParentEntity[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadParentEntity", inversedBy="manyToManyChildren")
     */
    protected $manyToManyParents;

    /**
     * @var UploadParentEntity|null
     * @ORM\ManyToOne(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadParentEntity", inversedBy="oneToManyChildren")
     */
    protected $manyToOneParent;

    public function __construct()
    {
        $this->manyToManyParents = new ArrayCollection();
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $file = null)
    {
        $this->file = $file;
        return null;
    }

    public function getRelPath()
    {
        return $this->relPath;
    }

    public function setRelPath($relPath)
    {
        $this->relPath = $relPath;
        return $this;
    }

    public function getRelDir()
    {
        return 'upload/child';
    }

    /**
     * @return UploadParentEntity|null
     */
    public function getOneToOneParent()
    {
        return $this->oneToOneParent;
    }

    /**
     * @param UploadParentEntity|null $parent
     * @return $this
     */
    public function setOneToOneParent($parent)
    {
        $this->oneToOneParent = $parent;
        return $this;
    }

    /**
     * @return ArrayCollection|UploadParentEntity[]
     */
    public function getManyToManyParents()
    {
        return $this->manyToManyParents;
    }

    /**
     * @return UploadParentEntity|null
     */
    public function getManyToOneParent()
    {
        return $this->manyToOneParent;
    }

    /**
     * @param UploadParentEntity|null $manyToOneParent
     * @return $this
     */
    public function setManyToOneParent($manyToOneParent)
    {
        $this->manyToOneParent = $manyToOneParent;
        return $this;
    }

}