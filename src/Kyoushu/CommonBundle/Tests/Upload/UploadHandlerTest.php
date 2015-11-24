<?php

namespace Kyoushu\CommonBundle\Tests\Upload;

use Doctrine\ORM\EntityManager;
use Kyoushu\CommonBundle\Tests\Entity\UploadEntity;
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

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return self::$kernel
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager')
        ;
    }

    public function testProcess()
    {

        $cacheDir = self::$kernel->getContainer()->getParameter('kernel.cache_dir');
        $tempDir = sprintf('%s/uploads', $cacheDir);

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $handler = new UploadHandler($tempDir);

        $upload = new UploadEntity();
        $upload->setFile(new File($sourcePath));

        $handler->process($upload);

        $this->assertNull($upload->getFile());
        $this->assertNotNull($upload->getRelPath());

        $path = sprintf('%s/%s', $tempDir, $upload->getRelPath());
        $this->assertFileExists($path);

        $this->assertRegExp('/^uploads\/foo\/bar\/test_[0-9a-z]{7}\.txt$/', $upload->getRelPath());

    }

    public function testProcessEventListener()
    {

        /** @var UploadHandler  $uploadHandler */
        $uploadHandler = self::$kernel->getContainer()->get('kyoushu_common.upload.handler');
        $webDir = $uploadHandler->getWebDir();

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $upload = new UploadEntity();
        $upload->setFile(new File($sourcePath));

        $manager = $this->getEntityManager();
        $manager->persist($upload);
        $manager->flush();

        $path = sprintf('%s/%s', $webDir, $upload->getRelPath());
        $this->assertFileExists($path);

        $this->assertRegExp('/^uploads\/foo\/bar\/test_[0-9a-z]{7}\.txt$/', $upload->getRelPath());

    }

}