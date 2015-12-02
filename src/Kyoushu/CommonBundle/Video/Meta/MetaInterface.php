<?php

namespace Kyoushu\CommonBundle\Video\Meta;

use Kyoushu\CommonBundle\Image\Dimensions;
use Kyoushu\CommonBundle\Video\Thumbnail;
use Kyoushu\CommonBundle\Video\VideoInterface;

interface MetaInterface
{

    /**
     * @return MetaFactory
     */
    public function getFactory();

    /**
     * @return array
     */
    public function getSupportedTypes();

    /**
     * @return string
     */
    public function getVendorId();

    /**
     * @return array
     */
    public function getVendorData();

    /**
     * @return VideoInterface
     */
    public function getVideo();

    /**
     * @return string|null
     */
    public function getTitle();

    /**
     * @return string|null
     */
    public function getAuthor();

    /**
     * @return string|null
     */
    public function getDescription();

    /**
     * @return Thumbnail|null
     */
    public function getThumbnail();

    /**
     * @return Dimensions|null
     */
    public function getDimensions();

}