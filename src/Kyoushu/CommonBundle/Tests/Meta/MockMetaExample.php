<?php

namespace Kyoushu\CommonBundle\Tests\Meta;

class MockMetaExample
{

    /**
     * @var string
     */
    public $stringProperty;

    /**
     * @var null
     */
    public $nullProperty;

    /**
     * @var MockMetaExample
     */
    public $selfProperty;

    /**
     * @var int
     */
    public $intProperty;

    /**
     * @var bool
     */
    public $boolProperty;

    /**
     * @var \DateTime
     */
    public $datetimeProperty;

    /**
     * @var array
     */
    public $arrayProperty;

    /**
     * @var \DateTime[]
     */
    public $objectArrayProperty;

    /**
     * @var null|string
     */
    public $mixedProperty;

}