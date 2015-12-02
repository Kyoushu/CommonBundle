<?php

namespace Kyoushu\CommonBundle\Video\Meta;

use Kyoushu\CommonBundle\Exception\VideoException;
use Kyoushu\CommonBundle\Video\VideoInterface;

class MetaFactory
{

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * MetaFactory constructor.
     * @param string $cacheDir
     * @param string $webDir
     * @throws VideoException
     */
    public function __construct($cacheDir, $webDir)
    {
        $this->cacheDir = $cacheDir;


        $this->webDir = realpath($webDir);
        if(!file_exists($this->webDir)){
            throw new VideoException(sprintf(
                'The directory %s does not exist',
                $this->webDir
            ));
        }
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @return string
     */
    public function getWebDir()
    {
        return $this->webDir;
    }

    /**
     * @param VideoInterface $video
     * @return YouTubeMeta
     * @throws VideoException
     */
    public function createMeta(VideoInterface $video)
    {
        if($video->getType() === VideoInterface::TYPE_YOUTUBE){
            return new YouTubeMeta($video, $this);
        }

        throw new VideoException(sprintf(
            'No meta class available for %s video types',
            $video->getType()
        ));
    }

}