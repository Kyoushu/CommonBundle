<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

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
     * @ORM\OneToOne(targetEntity="Kyoushu\CommonBundle\Tests\Entity\UploadParentEntity", inversedBy="child")
     */
    protected $parent;

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
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param UploadParentEntity|null $parent
     * @return $this
     */
    public function setParent(UploadParentEntity $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

}