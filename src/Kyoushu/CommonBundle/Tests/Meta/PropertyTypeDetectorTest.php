<?php

namespace Kyoushu\CommonBundle\Tests\Meta;

use Kyoushu\CommonBundle\Meta\PropertyTypeDetector;

class PropertyTypeDetectorTest extends \PHPUnit_Framework_TestCase
{

    public function testDetect()
    {

        $class = '\Kyoushu\CommonBundle\Tests\Meta\MockMetaExample';

        $this->assertEquals('string', PropertyTypeDetector::detect($class, 'stringProperty'));
        $this->assertEquals('NULL', PropertyTypeDetector::detect($class, 'nullProperty'));
        $this->assertEquals('\Kyoushu\CommonBundle\Tests\Meta\MockMetaExample', PropertyTypeDetector::detect($class, 'selfProperty'));
        $this->assertEquals('integer', PropertyTypeDetector::detect($class, 'intProperty'));
        $this->assertEquals('boolean', PropertyTypeDetector::detect($class, 'boolProperty'));
        $this->assertEquals('\DateTime', PropertyTypeDetector::detect($class, 'datetimeProperty'));
        $this->assertEquals('array', PropertyTypeDetector::detect($class, 'arrayProperty'));
        $this->assertEquals('array', PropertyTypeDetector::detect($class, 'objectArrayProperty'));
        $this->assertEquals('string', PropertyTypeDetector::detect($class, 'mixedProperty'));

        $this->assertEquals('NULL', PropertyTypeDetector::detect($class, 'nullMethod'));
        $this->assertEquals('integer', PropertyTypeDetector::detect($class, 'intMethod'));

    }

}