<?php

namespace Kyoushu\CommonBundle\Image;

use Kyoushu\CommonBundle\Exception\ImageException;

abstract class AbstractImage implements ImageInterface
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Dimensions
     */
    protected $dimensions;

    public function __construct($path)
    {

        if(!file_exists($path)){
            throw new ImageException(sprintf(
                'The file %s does not exist',
                $path
            ));
        }

        $this->path = realpath($path);

        $info = getimagesize($path);

        switch($info[2]){
            case IMAGETYPE_JPEG:
                $this->type = self::TYPE_JPEG;
                break;
            case IMAGETYPE_GIF:
                $this->type = self::TYPE_GIF;
                break;
            case IMAGETYPE_PNG:
                $this->type = self::TYPE_PNG;
                break;
            default:
                throw new ImageException(sprintf('Unsupported image type #%s for %s', $info[2], $path));
        }

        $this->dimensions = new Dimensions($info[0], $info[1]);

    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Dimensions
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

}