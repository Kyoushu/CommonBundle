<?php

namespace Kyoushu\CommonBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as KernelTestCaseBase;

class KernelTestCase extends KernelTestCaseBase
{
    /**
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'Kyoushu\CommonBundle\Tests\AppKernel';
    }

    /**
     * @param array $options
     * @return AppKernel
     */
    protected static function createKernel(array $options = array())
    {
        /** @var AppKernel $kernel */
        $kernel = parent::createKernel($options);
        if(isset($options['prepare_doctrine']) && $options['prepare_doctrine'] === true){
            $kernel->setPrepareDoctrineAfterBoot(true);
        }
        return $kernel;
    }

}