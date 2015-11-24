<?php

namespace Kyoushu\CommonBundle\Upload;

use Kyoushu\CommonBundle\Exception\UploadException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHandler
{

    const REGEX_INVALID_FILENAME_CHARS = '/[^a-zA-Z0-9]+/';
    const REGEX_FILENAME_EXT = '/\.(?<ext>[^\.]+)$/';

    /**
     * @var string
     */
    protected $webDir;

    /**
     * @param string $webDir
     */
    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * @param File $file
     * @return string
     */
    protected function getNormalizedFilename(File $file)
    {
        if($file instanceof UploadedFile){
            $originalFilename = $file->getClientOriginalName();
            $size = $file->getClientSize();
        }
        else{
            $originalFilename = $file->getFilename();
            $size = $file->getSize();
        }

        $name = preg_replace(self::REGEX_FILENAME_EXT, '', $originalFilename);
        $name = preg_replace(self::REGEX_INVALID_FILENAME_CHARS, '_', $name);

        $hash = substr(
            sha1(
                json_encode(array(
                    time(),
                    $name,
                    $size
                ))
            ),
            0,
            7
        );

        $ext = $file->getExtension();
        if(!$ext){
            $ext = explode('.', $originalFilename);
            $ext = end($ext);
        }

        $filename = sprintf(
            '%s_%s.%s',
            $name,
            $hash,
            $ext
        );

        return $filename;
    }

    /**
     * @param UploadInterface $upload
     * @throws UploadException
     */
    public function assertUploadValid(UploadInterface $upload)
    {

        $relDir = $upload->getRelDir();

        if(!is_string($relDir)){
            throw new UploadException(sprintf(
                'Expected the method getRelDir in %s to return a string, %s provided',
                get_class($upload),
                gettype($relDir)
            ));
        }

        if(substr($relDir, 0, 1) === '/'){
            throw new UploadException(sprintf(
                'The method getRelDir in %s returned a string beginning with a forward slash',
                get_class($upload)
            ));
        }

    }

    /**
     * Moves file to target directory. Returns TRUE if successful
     *
     * @param UploadInterface $upload
     * @return bool
     * @throws UploadException
     */
    public function process(UploadInterface $upload)
    {
        $this->assertUploadValid($upload);

        $file = $upload->getFile();
        if($file === null) return false;

        $relDir = $upload->getRelDir();

        $filename = $this->getNormalizedFilename($file);

        $relPath = sprintf('%s/%s', $relDir, $filename);
        $dir = sprintf('%s/%s', $this->webDir, $relDir);
        $path = sprintf('%s/%s', $dir, $filename);

        $fs = new Filesystem();
        if(!$fs->exists($dir)){
            $fs->mkdir($dir);
        }

        $sourcePath = sprintf('%s/%s', $file->getPath(), $file->getFilename());

        $fs->copy($sourcePath, $path);
        $upload->setRelPath($relPath);
        $upload->setFile(null);

        return true;
    }

}