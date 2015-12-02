<?php

namespace Kyoushu\CommonBundle\Video\Meta;

use Kyoushu\CommonBundle\Tests\KernelTestCase;
use Kyoushu\CommonBundle\Video\Thumbnail;
use Kyoushu\CommonBundle\Video\VideoInterface;
use Symfony\Component\Filesystem\Filesystem;

class YouTubeMetaTest extends KernelTestCase
{

    const VIDEO_URL = 'https://www.youtube.com/watch?v=ScMzIvxBSi4';
    const VIDEO_SHARE_URL = 'https://youtu.be/ScMzIvxBSi4';
    const VIDEO_VENDOR_ID = 'ScMzIvxBSi4';
    const VIDEO_TITLE = 'Placeholder Video';
    const VIDEO_AUTHOR = 'BenMarquezTX';

    protected $cacheDir;

    protected $webDir;

    protected function setUp()
    {
        self::bootKernel();
        $this->cacheDir = self::$kernel->getContainer()->getParameter('kernel.cache_dir');

        $this->webDir = sprintf('%s/web', $this->cacheDir);
        $fs = new Filesystem();
        if(!$fs->exists($this->webDir)){
            $fs->mkdir($this->webDir);
        }
    }

    protected function tearDown()
    {
        $fs = new Filesystem();
        if($fs->exists($this->cacheDir)){
            $fs->remove($this->cacheDir);
        }
    }

    /**
     * @param string $url
     * @return \PHPUnit_Framework_MockObject_MockObject|VideoInterface
     */
    protected function createVideo($url)
    {
        $video = $this->getMock('\Kyoushu\CommonBundle\Video\VideoInterface');
        $video->method('getUrl')->willReturn($url);
        $video->method('getType')->willReturn(VideoInterface::TYPE_YOUTUBE);
        return $video;
    }

    /**
     * @return MetaFactory
     */
    protected function createMetaFactory()
    {
        return new MetaFactory($this->cacheDir, $this->webDir);
    }

    /**
     * @param VideoInterface $video
     * @return YouTubeMeta
     */
    protected function createMeta(VideoInterface $video)
    {
        $factory = $this->createMetaFactory();
        return new YouTubeMeta($video, $factory);
    }

    /**
     * @throws \Kyoushu\CommonBundle\Exception\VideoException
     */
    public function testVideoFromUrl()
    {
        $video = $this->createVideo(self::VIDEO_URL);
        $meta = $this->createMeta($video);

        $this->assertEquals(self::VIDEO_VENDOR_ID, $meta->getVendorId());
        $this->assertEquals(self::VIDEO_TITLE, $meta->getTitle());
        $this->assertEquals(self::VIDEO_AUTHOR, $meta->getAuthor());
        $this->assertNull($meta->getDescription()); // @todo
    }

    /**
     * @throws \Kyoushu\CommonBundle\Exception\VideoException
     */
    public function testVideoFromShareUrl()
    {
        $video = $this->createVideo(self::VIDEO_SHARE_URL);
        $meta = $this->createMeta($video);

        $this->assertEquals(self::VIDEO_VENDOR_ID, $meta->getVendorId());
        $this->assertEquals(self::VIDEO_TITLE, $meta->getTitle());
        $this->assertEquals(self::VIDEO_AUTHOR, $meta->getAuthor());
        $this->assertNull($meta->getDescription()); // @todo
    }

    public function testThumbnail()
    {

        $video = $this->createVideo(self::VIDEO_URL);
        $meta = $this->createMeta($video);

        $thumbnail = $meta->getThumbnail();

        $this->assertEquals(
            sprintf(
                '%s/media/youtube/thumbs/%s.jpg',
                $this->webDir,
                self::VIDEO_VENDOR_ID
            ),
            $thumbnail->getPath()
        );

        $this->assertEquals(
            sprintf(
                '/media/youtube/thumbs/%s.jpg',
                self::VIDEO_VENDOR_ID
            ),
            $thumbnail->getUrl()
        );

        $this->assertEquals(Thumbnail::TYPE_JPEG, $thumbnail->getType());
        $this->assertEquals(self::VIDEO_TITLE, $thumbnail->getAltText());

        $dimensions = $thumbnail->getDimensions();
        $this->assertEquals(480, $dimensions->getWidth());
        $this->assertEquals(360, $dimensions->getHeight());
        $this->assertEquals(1.333, round($dimensions->getAspectRatio(), 3));

    }

}