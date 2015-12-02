<?php

namespace Kyoushu\CommonBundle\Video\Meta;

use Kyoushu\CommonBundle\Cache\Cache;
use Kyoushu\CommonBundle\Exception\VideoException;
use Kyoushu\CommonBundle\Video\Thumbnail;
use Kyoushu\CommonBundle\Video\VideoInterface;
use Symfony\Component\Filesystem\Filesystem;

class YouTubeMeta extends AbstractMeta
{

    const REGEX_VENDOR_ID_URL = '/[\?&]v=(?<vendor_id>[^\?&#]+)/';
    const REGEX_VENDOR_ID_SHARE_URL = '/^https?:\/\/youtu.be\/(?<vendor_id>[^\?&#]+)/';

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @param VideoInterface $video
     * @param MetaFactory $factory
     */
    public function __construct(VideoInterface $video, MetaFactory $factory)
    {
        parent::__construct($video, $factory);
        $this->cache = new Cache('kyoushu/video/meta/youtube', $this->getFactory()->getCacheDir());
    }


    public function getSupportedTypes()
    {
        return array(VideoInterface::TYPE_YOUTUBE);
    }

    /**
     * @return string
     * @throws VideoException
     */
    public function getVendorId()
    {
        $url = $this->getVideo()->getUrl();

        if(preg_match(self::REGEX_VENDOR_ID_URL, $url, $match)){
            return $match['vendor_id'];
        }

        if(preg_match(self::REGEX_VENDOR_ID_SHARE_URL, $url, $match)){
            return $match['vendor_id'];
        }

        throw new VideoException(sprintf(
            'Could not derive YouTube video ID from URL %s',
            $url
        ));
    }

    public static function unserializeData($dataString)
    {
        parse_str($dataString, $data);
        if(!$data) return null;

        $nestedDataKeys = array('adaptive_fmts', 'url_encoded_fmt_stream_map');
        $formatListKeys = array('fmt_list');

        array_walk($data, function(&$value, $key) use ($nestedDataKeys, $formatListKeys){
            if(in_array($key, $nestedDataKeys)){
                $value = YouTubeMeta::unserializeData($value);
            }

            if(in_array($key, $formatListKeys)){
                $value = explode(',', $value);
                array_walk($value, function(&$value){
                    $value = explode('/', $value);
                    $value[1] = explode('x', $value[1]);
                    $value['_dimensions'] = $value[1];
                });
            }

        });

        return $data;
    }

    /**
     * @return array
     * @throws VideoException
     */
    public function getVendorData()
    {
        $meta = $this;
        return $this->cache->get($this->getVendorId(), Cache::TTL_NEVER_EXPIRES, function() use ($meta){

            $url = sprintf('http://youtube.com/get_video_info?video_id=%s', $meta->getVendorId());
            $response = file_get_contents($url);

            if(!$response){
                throw new VideoException(sprintf(
                    'Could not download YouTube video meta data from %s',
                    $url
                ));
            }

            $data =self::unserializeData($response);

            if($data === null){
                throw new VideoException(sprintf(
                    'Could not parse YouTube video meta data from %s',
                    $url
                ));
            }

            return $data;

        });
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        $data = $this->getVendorData();
        if(!isset($data['title'])) return null;
        return $data['title'];
    }

    public function getAuthor()
    {
        $data = $this->getVendorData();
        if(!isset($data['author'])) return null;
        return $data['author'];
    }

    /**
     * @return null
     * @todo
     */
    public function getDescription()
    {
        return null;
    }

    /**
     * @return Thumbnail
     * @throws VideoException
     */
    public function getThumbnail()
    {
        $data = $this->getVendorData();

        $url = sprintf(
            'http://img.youtube.com/vi/%s/0.jpg',
            $this->getVendorId()
        );

        $path = sprintf(
            '%s/media/youtube/thumbs/%s.jpg',
            $this->getFactory()->getWebDir(),
            $this->getVendorId()
        );

        $fs = new Filesystem();
        if(!$fs->exists($path)){
            $dir = dirname($path);
            if(!$fs->exists($dir)){
                $fs->mkdir($dir);
            }
            $fs->copy($url, $path);
        }

        return new Thumbnail($this, $path);
    }

}