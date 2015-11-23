<?php

namespace Kyoushu\CommonBundle\Tests\DependencyInjection;

use Kyoushu\CommonBundle\Tests\KernelTestCase;

class KyoushuCommonExtensionTest extends KernelTestCase
{

    public function testBundle()
    {

        $kernel = $this->createKernel();
        $kernel->boot();

        $bundle = $kernel->getBundle('KyoushuCommonBundle');
        $this->assertInstanceOf('Kyoushu\CommonBundle\KyoushuCommonBundle', $bundle);

    }

}