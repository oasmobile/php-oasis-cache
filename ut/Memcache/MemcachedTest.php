<?php

namespace Oasis\Cache\UnitTest\Memcache;

use Oasis\Cache\Memcached;
use Oasis\Cache\UnitTest\BaseTestCase;

/**
 * Class MemcachedTest
 * @package Oasis\Cache\UnitTest\Memcache
 *
 * Below cases well cover all referenced cache methods in \Doctrine\Common\Cache\MemcachedCache
 *
 * and all have passed under libmemcached version => 1.0.18
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

    public function testAllOtherMethodsSimply()
    {

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
        $this->cache->addByKey($serverKey, $key, $val);
        $this->cache->replace($key, $val);
        $this->cache->replaceByKey($serverKey, $key, $val);
        $this->cache->deleteByKey($serverKey, $key);
        $this->cache->deleteMultiByKey($serverKey, $keys);

        //
        $cas_token = 1;
        $this->cache->get($key, null, $cas_token);
        $this->cache->cas($cas_token, $key, $val);
        $this->cache->casByKey($cas_token, $serverKey, $key, $val);
        //
        $this->cache->setOption(Memcached::OPT_COMPRESSION, false);
        $this->cache->set($key, $val);
        $this->cache->append($key, '(2)');
        $this->cache->appendByKey($serverKey, $key, '(2)');
        $this->cache->prepend($key, '<1>');
        $this->cache->prependByKey($serverKey, $key, '<1>');

        $this->cache->setOption(Memcached::OPT_COMPRESSION, true);

        //
        $this->cache->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
        $this->cache->set($key, 1);
        $this->cache->increment($key, 10);
        $this->cache->incrementByKey($serverKey, $key, 10);
        $this->cache->decrement($key, 1);
        $this->cache->decrementByKey($serverKey, $key, 1);
    }

}
