<?php

namespace Kyoushu\CommonBundle\Tests\Upload;

use Kyoushu\CommonBundle\Upload\UploadInterface;
use Symfony\Component\HttpFoundation\File\File;

class MockUpload implements UploadInterface
{

    /**
     * @var File|null
     */
    protected $file;

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
     * @return mixed
     */
    public function getRelPath()
    {
        return $this->relPath;
    }

    /**
     * @param string|null $relPath
     * @return $this
     */
    public function setRelPath($relPath)
    {
        $this->relPath = $relPath;
        return $this;
    }

    public function getRelDir()
    {
        return 'uploads/foo/bar';
    }

}