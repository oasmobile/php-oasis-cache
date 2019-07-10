<?php

/**
 * Created by PhpStorm.
 * User: xuchang
 * Date: 2019/7/2
 * Time: 16:03
 */

namespace Oasis\Cache;

/**
 * Class Memcached
 * @package Oasis\Cache
 *
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

        $this->setOption(
            self::OPT_PREFIX_KEY,
            sprintf("%s:", $this->namespace)
        );
    }
}

