<?php

namespace Kyoushu\CommonBundle\Tests\Cache;

use Kyoushu\CommonBundle\Cache\Cache;
use Kyoushu\CommonBundle\Tests\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class CacheTest extends KernelTestCase
{

    protected $cacheDir;

    protected function setUp()
    {
        self::bootKernel();
        $this->cacheDir = self::$kernel->getContainer()->getParameter('kernel.cache_dir');
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->cacheDir);
    }

    public function testCacheFileCreation()
    {
        $cache = new Cache('file_creation', $this->cacheDir);
        $cache->set('foo', 'bar');
        $this->assertFileExists($this->cacheDir . '/file_creation/0b/ee/0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33.ser');
    }

    public function createIteratorClosure()
    {
        $i = 0;
        return function() use (&$i){
            $i++;
            return $i;
        };
    }

    public function testAlwaysLive()
    {
        $cache = new Cache('file_creation', $this->cacheDir);

        $closure = $this->createIteratorClosure();

        $this->assertEquals(1, $cache->get('foo', Cache::TTL_ALWAYS_LIVE, $closure));
        $this->assertEquals(2, $cache->get('foo', Cache::TTL_ALWAYS_LIVE, $closure));
        $this->assertEquals(3, $cache->get('foo', Cache::TTL_ALWAYS_LIVE, $closure));
        $this->assertEquals(4, $cache->get('foo', Cache::TTL_ALWAYS_LIVE, $closure));
    }

    public function testExpires()
    {
        $cache = new Cache('file_creation', $this->cacheDir);

        $closure = $this->createIteratorClosure();

        $this->assertEquals(1, $cache->get('foo', 2, $closure));
        sleep(1);
        $this->assertEquals(1, $cache->get('foo', 2, $closure));
        sleep(2);
        $this->assertEquals(2, $cache->get('foo', 2, $closure));
    }

    public function testNeverExpires()
    {
        $cache = new Cache('file_creation', $this->cacheDir);

        $closure = $this->createIteratorClosure();

        $this->assertEquals(1, $cache->get('foo', Cache::TTL_NEVER_EXPIRES, $closure));
        sleep(1);
        $this->assertEquals(1, $cache->get('foo', Cache::TTL_NEVER_EXPIRES, $closure));
    }

}