<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-01-19
 * Time: 21:00
 */

namespace Oasis\Mlib\Utils;

class CommonUtils
{
    public static function isRunningFromCommandLine()
    {
        static $isCli = null;
        if ($isCli === null) {
            $isCli = (
                !isset($_SERVER['SERVER_SOFTWARE'])
                && (
                    php_sapi_name() == 'cli'
                    || (is_numeric($_SERVER['argc']) && $_SERVER['argc'] > 0)
                )
            );
        }

        return $isCli;
    }
}
