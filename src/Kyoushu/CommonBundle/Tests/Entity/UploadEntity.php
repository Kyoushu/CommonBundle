<?php

namespace Kyoushu\CommonBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kyoushu\CommonBundle\Entity\Traits\IdTrait;
use Kyoushu\CommonBundle\Upload\UploadInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity()
 */
class UploadEntity implements UploadInterface
{

    use IdTrait;

    /**
     * @var File|null
     */
    protected $file;

    /**
     * @var string|null
     */
    protected $relPath;

    /**
     * @return null|File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param null|File $file
     * @return $this
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getRelPath()
    {
        return $this->relPath;
    }

    /**
     * @param null|string $relPath
     * @return $this
     */
    public function setRelPath($relPath)
    {
        $this->relPath = $relPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelDir()
    {
        return 'uploads/foo/bar';
    }

}