<?php

namespace Kyoushu\CommonBundle\Video;

interface VideoInterface
{

    const TYPE_YOUTUBE = 'youtube';

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getType();

}