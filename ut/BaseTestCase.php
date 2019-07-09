<?php

namespace Oasis\Cache\UnitTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BaseTestCase
 * @package Oasis\Cache\UnitTest
 */
class BaseTestCase extends TestCase
{

    protected $sdkConfig = [];

    protected function setUp()
    {

        parent::setUp();

        $this->sdkConfig = $this->getTestSDKConfig();
    }

    protected function getTestSDKConfig()
    {

        static $config = null;
        if ($config) {
            return $config;
        }

        $yml    = Yaml::parse(file_get_contents(__DIR__ . "/ut.yml"));
        $config = $yml['sdk.config'];

        return $config;
    }

}
