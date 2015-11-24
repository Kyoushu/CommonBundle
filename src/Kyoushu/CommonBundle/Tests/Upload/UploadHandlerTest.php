<?php

namespace Kyoushu\CommonBundle\Tests\Upload;

use Kyoushu\CommonBundle\Tests\KernelTestCase;
use Kyoushu\CommonBundle\Upload\UploadHandler;
use Symfony\Component\HttpFoundation\File\File;

class UploadHandlerTest extends KernelTestCase
{

    protected function setUp()
    {
        self::bootKernel(array(
            'prepare_doctrine' => true
        ));
    }

    public function testProcess()
    {

        $cacheDir = self::$kernel->getContainer()->getParameter('kernel.cache_dir');
        $tempDir = sprintf('%s/uploads', $cacheDir);

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $handler = new UploadHandler($tempDir);

        $upload = new MockUpload();
        $upload->setFile(new File($sourcePath));

        $handler->process($upload);

        $this->assertNull($upload->getFile());
        $this->assertNotNull($upload->getRelPath());

        $path = sprintf('%s/%s', $tempDir, $upload->getRelPath());
        $this->assertFileExists($path);

    }

}