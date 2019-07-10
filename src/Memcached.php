<?php

/**
 * Created by PhpStorm.
 * User: xuchang
 * Date: 2019/7/2
 * Time: 16:03
 */

namespace Oasis\Cache;

/**
 * Class DCMemecached
 * @package Oasis\DeployCenter\Database
 */
class Memcached extends \Memcached
{

    /**
     * @var string
     */
    private $namespace = '';

    public function getNamespace()
    {

        return $this->namespace;
    }

    public function setNamespace($namespace)
    {

        $this->namespace = (string)$namespace;
    }

    /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
    public function set($key, $value, $expiration = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::set($key, $value, $expiration);
    }

    private function getNamespacedKey($key)
    {

        return sprintf('%s:%s', $this->namespace, $key);
    }

    public function get(
        $key, /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */ $cache_cb = null, $get_flags = null
    )
    {

        $key = $this->getNamespacedKey($key);

        return parent::get($key, $cache_cb, $get_flags);
    }

    public function getByKey(
        $server_key, $key, /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
        $cache_cb = null, $get_flags = null
    )
    {

        $key = $this->getNamespacedKey($key);

        return parent::getByKey($server_key, $key, $cache_cb, $get_flags);
    }

    public function getMulti(array $keys, $get_flags = null)
    {

        $keys = $this->prefixKeys($keys);

        return parent::getMulti($keys, $get_flags);
    }

    private function prefixKeys(array $keys)
    {

        $prefixedKeys = [];
        foreach ($keys as $key) {
            $prefixedKeys[] = $this->getNamespacedKey($key);
        }

        return $prefixedKeys;
    }

    public function getMultiByKey($server_key, array $keys, $get_flags = null)
    {

        $keys = $this->prefixKeys($keys);

        return parent::getMultiByKey($server_key, $keys, $get_flags);
    }

    public function getDelayed(
        array $keys, $with_cas = null,
        /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */ $value_cb = null
    )
    {

        $keys = $this->prefixKeys($keys);

        return parent::getDelayed($keys, $with_cas, $value_cb);
    }

    public function getDelayedByKey(
        $server_key, array $keys, $with_cas = null,
        /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */ $value_cb = null
    )
    {

        $keys = $this->prefixKeys($keys);

        return parent::getDelayedByKey($server_key, $keys, $with_cas, $value_cb);
    }

    public function setByKey($server_key, $key, $value, $expiration = 0, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::setByKey($server_key, $key, $value, $expiration);
    }

    public function touch($key, $expiration = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::touch($key, $expiration);
    }

    public function touchByKey($server_key, $key, $expiration)
    {

        $key = $this->getNamespacedKey($key);

        return parent::touchByKey($server_key, $key, $expiration);
    }

    public function setMulti(array $items, $expiration = 0, $udf_flags = 0)
    {

        $items = $this->prefixArrayKeys($items);

        return parent::setMulti($items, $expiration);
    }

    private function prefixArrayKeys(array $items)
    {

        $prefixed = [];
        foreach ($items as $key => $value) {
            $prefixed[$this->getNamespacedKey($key)] = $value;
        }

        return $prefixed;
    }

    public function setMultiByKey($server_key, array $items, $expiration = 0, $udf_flags = 0)
    {

        $items = $this->prefixArrayKeys($items);

        return parent::setMultiByKey($server_key, $items, $expiration);
    }

    public function cas($cas_token, $key, $value, $expiration = 0, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::cas($cas_token, $key, $value, $expiration);
    }

    public function casByKey($cas_token, $server_key, $key, $value, $expiration = 0, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::casByKey($cas_token, $server_key, $key, $value, $expiration, $udf_flags);
    }

    public function add($key, $value, $expiration = 0, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::add($key, $value, $expiration);
    }

    public function addByKey($server_key, $key, $value, $expiration = 0, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::addByKey($server_key, $key, $value, $expiration);
    }

    public function append($key, $value, $expiration = null)
    {

        $key = $this->getNamespacedKey($key);

        return parent::append($key, $value);
    }

    public function appendByKey($server_key, $key, $value, $expiration = null)
    {

        $key = $this->getNamespacedKey($key);

        return parent::appendByKey($server_key, $key, $value);
    }

    public function prepend($key, $value, $expiration = null)
    {

        $key = $this->getNamespacedKey($key);

        return parent::prepend($key, $value);
    }

    public function prependByKey($server_key, $key, $value, $expiration = null)
    {

        $key = $this->getNamespacedKey($key);

        return parent::prependByKey($server_key, $key, $value);
    }

    public function replace($key, $value, $expiration = null, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::replace($key, $value, $expiration);
    }

    public function replaceByKey($server_key, $key, $value, $expiration = null, $udf_flags = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::replaceByKey($server_key, $key, $value, $expiration);
    }

    public function delete($key, $time = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::delete($key, $time);
    }

    public function deleteMulti(/** @noinspection PhpSignatureMismatchDuringInheritanceInspection */ $keys, $time = null
    )
    {

        $keys = $this->prefixKeys($keys);

        return parent::deleteMulti($keys, $time);
    }

    public function deleteByKey($server_key, $key, $time = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::deleteByKey($server_key, $key, $time);
    }

    public function deleteMultiByKey(
        $server_key, /** @noinspection PhpSignatureMismatchDuringInheritanceInspection */
        $keys, $time = null
    )
    {

        $keys = $this->prefixKeys($keys);

        return parent::deleteMultiByKey($server_key, $keys, $time);
    }

    public function increment($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::increment($key, $offset, $initial_value, $expiry);
    }

    public function decrement($key, $offset = 1, $initial_value = 0, $expiry = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::decrement($key, $offset, $initial_value, $expiry);
    }

    public function incrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::incrementByKey($server_key, $key, $offset, $initial_value, $expiry);
    }

    public function decrementByKey($server_key, $key, $offset = 1, $initial_value = 0, $expiry = 0)
    {

        $key = $this->getNamespacedKey($key);

        return parent::decrementByKey($server_key, $key, $offset, $initial_value, $expiry);
    }

}

