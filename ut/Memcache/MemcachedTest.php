<?php

namespace Oasis\Cache\UnitTest\Memcache;

use Oasis\Cache\Memcached;
use Oasis\Cache\UnitTest\BaseTestCase;

/**
 * Class MemcachedTest
 * @package Oasis\Cache\UnitTest\Memcache
 *
 *  Below cases cover all cache usage in \Doctrine\Common\Cache\MemcachedCache
 *  but do cover all cache usage in \Doctrine\Common\Cache\MemcachedCache
 *
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

    public function testAdd()
    {

        $key1 = 'k001';
        $val1 = 'v001';

        $this->cache->add($key1, $val1);

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

    public function testGetSetMuti()
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

    public function testDelMuti()
    {

        $items = [
            'key1' => 'v01',
            'key2' => 'v02',
            'key3' => 'v03',
        ];

        $this->cache->setMulti($items);
        $getVals = $this->cache->getMulti(array_keys($items));
        $this->assertEquals(array_values($items), array_values($getVals), json_encode($getVals));

        // delete items
        $delRet = $this->cache->deleteMulti(array_keys($items));
        $this->assertEquals(count($items), count($delRet));
        foreach ($delRet as $k => $v) {
            $this->assertEquals(true, $v);
        }
    }

    public function testDelete()
    {

        $key1 = 'k001';
        $val1 = 'v001';

        $this->cache->add($key1, $val1);

        $testVal1 = $this->cache->get($key1);
        $this->assertEquals($val1, $testVal1, $testVal1);

        $delRet = $this->cache->delete($key1);
        $this->assertEquals(true, $delRet);

        $getRet = $this->cache->get($key1);
        $this->assertEquals(false, $getRet);
    }

    public function testOthersSimply()
    {

        $cas_token = 'cas_token-001';
        $serverKey = 'server-key-01';
        $key       = 'key-1';
        $val       = 'val-1';
        $keys      = ['key1', 'key2'];
        $items     = [
            'key1' => 'v01',
            'key2' => 'v02',
            'key3' => 'v03',
        ];

        $this->cache->getByKey($serverKey, $key);
        $this->cache->getMultiByKey($serverKey, $keys);
        $this->cache->getDelayed($keys);
        $this->cache->getDelayedByKey($serverKey, $keys);
        $this->cache->setByKey($serverKey, $key, $val);
        $this->cache->touch($key);
        $this->cache->touchByKey($serverKey, $key, time());
        $this->cache->setMultiByKey($serverKey, $items);
//        $this->cache->cas($cas_token, $key, $val);
        $this->cache->addByKey($serverKey, $key, $val);
        $this->cache->replace($key,$val);
        $this->cache->replaceByKey($serverKey,$key,$val);
        $this->cache->deleteByKey($serverKey,$key);
        $this->cache->deleteMultiByKey($serverKey,$keys);
    }

}
