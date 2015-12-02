<?php

namespace Kyoushu\CommonBundle\Video\Meta;

use Kyoushu\CommonBundle\Exception\VideoException;
use Kyoushu\CommonBundle\Image\Dimensions;
use Kyoushu\CommonBundle\Video\VideoInterface;

abstract class AbstractMeta implements MetaInterface
{

    /**
     * @var MetaFactory
     */
    protected $factory;

    /**
     * @var VideoInterface
     */
    protected $video;

    /**
     * AbstractMeta constructor.
     * @param VideoInterface $video
     * @param MetaFactory $factory
     */
    public function __construct(VideoInterface $video, MetaFactory $factory)
    {
        $this->assertSupportsType($video->getType());
        $this->video = $video;
        $this->factory = $factory;
    }

    /**
     * @param string $type
     * @throws VideoException
     */
    protected function assertSupportsType($type)
    {
        if(!in_array($type, $this->getSupportedTypes())){
            throw new VideoException(sprintf(
                '%s does not support %s video types (supported types: %s)',
                get_class($this),
                $type,
                implode(', ', $this->getSupportedTypes())
            ));
        }
    }

    /**
     * @return MetaFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return VideoInterface
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions()
    {
        return $this->getThumbnail()->getDimensions();
    }

}