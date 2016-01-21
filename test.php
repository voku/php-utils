#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-12-29
 * Time: 21:09
 */

use Oasis\Mlib\Utils\CommonUtils;

require_once "vendor/autoload.php";

declare(ticks = 10);

CommonUtils::registerMemoryMonitorForTick();

$s = str_repeat(' ', 1024 * 1024);

$a = $b = '';
for ($i = 0; $i < 100000; ++$i){
    $a .= $s;
}
