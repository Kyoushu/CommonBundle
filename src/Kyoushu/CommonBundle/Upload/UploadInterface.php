<?php

namespace Kyoushu\CommonBundle\Upload;

use Symfony\Component\HttpFoundation\File\File;

interface UploadInterface
{

    /**
     * @return File|null
     */
    public function getFile();

    /**
     * @param File|null $file
     * @return $this
     */
    public function setFile(File $file = null);

    /**
     * @return string|null
     */
    public function getRelPath();

    /**
     * @param string|null $relPath
     * @return $this
     */
    public function setRelPath($relPath);

    /**
     * @return string
     */
    public function getRelDir();

}