<?php

namespace Kyoushu\CommonBundle\Cache;

use Symfony\Component\Filesystem\Filesystem;

class Cache
{

    const TTL_NEVER_EXPIRES = -1;
    const TTL_ALWAYS_LIVE = 0;

    const TTL_MINUTE = 60;
    const TTL_HOUR = 3600;
    const TTL_DAY = 86400;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name
     * @param string $cacheDir
     */
    public function __construct($name, $cacheDir)
    {
        $this->name = $name;
        $this->cacheDir = $cacheDir;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function createKeyHash($key)
    {
        return sha1($key);
    }

    /**
     * @param string $key
     * @return string
     */
    public function createPath($key)
    {
        $hash = $this->createKeyHash($key);
        $relDir = implode('/', array_slice(str_split($hash, 2), 0, 2));
        return sprintf('%s/%s/%s/%s.ser', $this->cacheDir, $this->name, $relDir, $hash);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $path = $this->createPath($key);
        $dir = dirname($path);
        $fs = new Filesystem();

        if(!$fs->exists($dir)){
            $fs->mkdir($dir);
        }

        $serializedValue = serialize($value);
        $fs->dumpFile($path, $serializedValue);

        return $this;
    }

    /**
     * @param string $key
     * @param int $ttl
     * @param \Closure|null $defaultCallback
     * @return mixed|null
     */
    public function get($key, $ttl = self::TTL_NEVER_EXPIRES, \Closure $defaultCallback = null)
    {
        if($this->isExpired($key, $ttl)){
            $value = ($defaultCallback === null ? null : $defaultCallback());
            $this->set($key, $value);
            return $value;
        }
        else{
            $path = $this->createPath($key);
            $serializedValue = file_get_contents($path);
            $value = unserialize($serializedValue);
            return $value;
        }
    }

    /**
     * @param string $key
     */
    public function clear($key)
    {
        $path = $this->createPath($key);

        $fs = new Filesystem();
        if($fs->exists($path)){
            $fs->remove($path);
        }
    }

    /**
     * @param string $key
     * @return int|null
     */
    public function getAge($key)
    {
        $path = $this->createPath($key);
        $fs = new Filesystem();
        if(!$fs->exists($path)) return null;
        $now = time();
        $modified = filemtime($path);
        return $now - $modified;
    }

    /**
     * @param string $key
     * @param int $ttl
     * @return bool
     */
    public function isExpired($key, $ttl)
    {
        if($ttl === self::TTL_ALWAYS_LIVE) return true;
        $age = $this->getAge($key);
        if($age === null) return true;
        if($ttl === self::TTL_NEVER_EXPIRES) return false;
        return $age > $ttl;
    }

}