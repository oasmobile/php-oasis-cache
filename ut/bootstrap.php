<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-11-11
 * Time: 16:26
 */

use Oasis\Mlib\Logging\ConsoleHandler;
use Oasis\Mlib\Logging\MLogging;

require __DIR__ . "/../vendor/autoload.php";

(new ConsoleHandler())->install();
if (!in_array('-v', $_SERVER['argv'])
    && !in_array('--verbose', $_SERVER['argv'])
) {
    MLogging::setMinLogLevel('notice');
}
