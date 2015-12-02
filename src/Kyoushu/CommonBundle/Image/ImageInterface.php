<?php

namespace Kyoushu\CommonBundle\Image;

interface ImageInterface
{

    const TYPE_JPEG = 'jpeg';
    const TYPE_PNG = 'png';
    const TYPE_GIF = 'gif';

    /**
     * @return string|null
     */
    public function getUrl();

    /**
     * Absolute path to local image
     *
     * @return string|null
     */
    public function getPath();

    /**
     * @return string|null
     */
    public function getAltText();

    /**
     * @return Dimensions
     */
    public function getDimensions();

    /**
     * @return string
     */
    public function getType();

}