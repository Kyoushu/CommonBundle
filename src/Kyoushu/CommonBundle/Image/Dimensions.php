<?php

namespace Kyoushu\CommonBundle\Image;

class Dimensions
{

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        $this->width = (int)$width;
        $this->height = (int)$height;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getAspectRatio()
    {
        return $this->width / $this->height;
    }

}