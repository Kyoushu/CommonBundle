<?php

namespace Kyoushu\CommonBundle\Tests\Upload;

use Doctrine\ORM\EntityManager;
use Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity;
use Kyoushu\CommonBundle\Tests\Entity\UploadEntity;
use Kyoushu\CommonBundle\Tests\Entity\UploadParentEntity;
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

    public function testProcessEventListenerCascadeOneToOne()
    {

        /** @var UploadHandler  $uploadHandler */
        $uploadHandler = self::$kernel->getContainer()->get('kyoushu_common.upload.handler');
        $webDir = $uploadHandler->getWebDir();

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $parent = new UploadParentEntity();

        $child = new UploadChildEntity();
        $child->setFile(new File($sourcePath));

        $parent->setOneToOneChild($child);

        $manager = $this->getEntityManager();
        $manager->persist($parent);
        $manager->flush();

        $path = sprintf('%s/%s', $webDir, $child->getRelPath());
        $this->assertFileExists($path);

        $this->assertNotNull($parent->getOneToOneChild());
        $path = sprintf('%s/%s', $webDir, $parent->getOneToOneChild()->getRelPath());
        $this->assertFileExists($path);

        $fetchedChild = $this->getEntityManager()
            ->getRepository('Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity')
            ->find($child->getId());
        $this->assertNotNull($fetchedChild);

        $path = sprintf('%s/%s', $webDir, $fetchedChild->getRelPath());
        $this->assertFileExists($path);

    }

    public function testProcessEventListenerCascadeManyToMany()
    {

        /** @var UploadHandler $uploadHandler */
        $uploadHandler = self::$kernel->getContainer()->get('kyoushu_common.upload.handler');
        $webDir = $uploadHandler->getWebDir();

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $parent = new UploadParentEntity();

        $childOne = new UploadChildEntity();
        $childOne->setFile(new File($sourcePath));

        $childTwo = new UploadChildEntity();
        $childTwo->setFile(new File($sourcePath));

        $parent->addManyToManyChild($childOne);
        $parent->addManyToManyChild($childTwo);

        $manager = $this->getEntityManager();
        $manager->persist($parent);
        $manager->flush();

        $this->assertCount(2, $parent->getManyToManyChildren());

        foreach($parent->getManyToManyChildren() as $child){
            $path = sprintf('%s/%s', $webDir, $child->getRelPath());
            $this->assertFileExists($path);

            $fetchedChild = $this->getEntityManager()
                ->getRepository('Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity')
                ->find($child->getId());
            $this->assertNotNull($fetchedChild);

            $path = sprintf('%s/%s', $webDir, $fetchedChild->getRelPath());
            $this->assertFileExists($path);
        }

    }

    public function testProcessEventListenerCascadeOneToMany()
    {

        /** @var UploadHandler $uploadHandler */
        $uploadHandler = self::$kernel->getContainer()->get('kyoushu_common.upload.handler');
        $webDir = $uploadHandler->getWebDir();

        $sourcePath = sprintf('%s/../Resources/upload/test.txt', __DIR__);
        $this->assertFileExists($sourcePath);

        $parent = new UploadParentEntity();

        $childOne = new UploadChildEntity();
        $childOne->setFile(new File($sourcePath));

        $childTwo = new UploadChildEntity();
        $childTwo->setFile(new File($sourcePath));

        $parent->addOneToManyChild($childOne);
        $parent->addOneToManyChild($childTwo);

        $manager = $this->getEntityManager();
        $manager->persist($parent);
        $manager->flush();

        $this->assertCount(2, $parent->getOneToManyChildren());

        foreach($parent->getOneToManyChildren() as $child){
            $path = sprintf('%s/%s', $webDir, $child->getRelPath());
            $this->assertFileExists($path);

            $fetchedChild = $this->getEntityManager()
                ->getRepository('Kyoushu\CommonBundle\Tests\Entity\UploadChildEntity')
                ->find($child->getId());
            $this->assertNotNull($fetchedChild);

            $path = sprintf('%s/%s', $webDir, $fetchedChild->getRelPath());
            $this->assertFileExists($path);
        }

    }

}