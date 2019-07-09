<?php

namespace Oasis\Cache\UnitTest\Memcache;

use Oasis\Cache\Memcached;
use Oasis\Cache\UnitTest\BaseTestCase;

/**
 * Class MemcachedTest
 * @package Oasis\Cache\UnitTest\Memcache
 */
class MemcachedTest extends BaseTestCase
{

    /**
     * @var Memcached
     */
    private $cache = null;
    /**
     * @var string
     */
    private $namespace = 'sdktest';

    public function setUp()
    {

        parent::setUp();
        $this->initCache();
    }

    protected function initCache()
    {

        if ($this->cache != null) {
            return $this->cache;
        }

        $this->cache = new Memcached();
        $this->cache->addServer(
            $this->sdkConfig['memcached']['host'],
            $this->sdkConfig['memcached']['port']
        );
        $this->cache->setNamespace($this->namespace);

        return $this->cache;
    }

    public function testSet()
    {

        $key1 = 'k001';
        $val1 = 'v001';

        $this->cache->set($key1, $val1);

        $testVal1 = $this->cache->get($key1);

        $this->assertEquals($val1, $testVal1, $testVal1);
    }

    public function testNamespaceWork()
    {

        $key1 = 'k001';
        $val1 = 'v001';

        $this->cache->set($key1, $val1);

        $keys = $this->cache->getAllKeys();

        $this->assertContains(
            sprintf('%s:%s', $this->namespace, $key1),
            $keys,
            json_encode($keys)
        );
    }

    public function testMuti()
    {

        $items = [
            'key1' => 'v01',
            'key2' => 'v02',
            'key3' => 'v03',
        ];

        $this->cache->setMulti($items);
        $getVals = $this->cache->getMulti(array_keys($items));

        $this->assertEquals(array_values($items), array_values($getVals), json_encode($getVals));
    }

}
