<?php

namespace Kyoushu\CommonBundle\Video;

use Kyoushu\CommonBundle\Exception\ImageException;
use Kyoushu\CommonBundle\Image\AbstractImage;
use Kyoushu\CommonBundle\Video\Meta\MetaInterface;

class Thumbnail extends AbstractImage
{

    /**
     * @var MetaInterface
     */
    protected $meta;

    /**
     * @var string
     */
    protected $url;

    public function __construct(MetaInterface $meta, $path)
    {
        parent::__construct($path);
        $this->meta = $meta;

        $webDir = realpath($this->getMeta()->getFactory()->getWebDir());
        $webDirRegex = sprintf('/(?<web_dir>^%s\/)(?<rel_path>.+)/', preg_quote($webDir, '/'));

        if(!preg_match($webDirRegex, $this->path, $match)){
            throw new ImageException(sprintf(
                '%s is not in the web dir',
                $this->path
            ));
        }

        $this->url = sprintf('/%s', $match['rel_path']);
    }

    /**
     * @return MetaInterface
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @return VideoInterface
     */
    public function getVideo()
    {
        return $this->getMeta()->getVideo();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return null|string
     */
    public function getAltText()
    {
        return $this->getMeta()->getTitle();
    }

}