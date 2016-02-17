<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-02-15
 * Time: 10:55
 */

namespace Oasis\Mlib\Utils;

interface DataProviderInterface
{
    const INT_TYPE      = "requireInt";
    const FLOAT_TYPE    = "requireFloat";
    const STRING_TYPE   = "requireString";
    const ARRAY_TYPE    = "requireArray";
    const ARRAY_2D_TYPE = "requireArray2D";
    const BOOL_TYPE     = "requireBool";

    public function get($key, $type = self::STRING_TYPE, $isMandatory = false, $default = null);

    public function getMandatory($key, $type = self::STRING_TYPE);

    public function getOptional($key, $type = self::STRING_TYPE, $default = null);
}
