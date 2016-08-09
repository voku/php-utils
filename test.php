#! /usr/local/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-12-29
 * Time: 21:09
 */

use Oasis\Mlib\Utils\CaesarCipher;

require_once "vendor/autoload.php";

$a = 'abcdefg';

//var_dump(unpack('c*', $a));

$cipher = new CaesarCipher();
$result = $cipher->encrypt($a);
$back = $cipher->decrypt($result);
var_dump($back);
var_dump(unpack('C*', $back));
