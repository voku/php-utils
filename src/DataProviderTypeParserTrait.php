<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-02-15
 * Time: 10:56
 */

namespace Oasis\Mlib\Utils;

use Oasis\Mlib\Utils\Exceptions\InvalidDataTypeException;

trait DataProviderTypeParserTrait
{
    protected function parseValue($type, &$val)
    {
        return $this->{$type}($val);
    }

    protected function requireInt(&$val)
    {
        if (is_null($val)) {
            return 0;
        }

        if (!is_scalar($val)) {
            throw new InvalidDataTypeException("Non-scalar value encountered when int is expected");
        }

        return intval($val);
    }

    protected function requireFloat(&$val)
    {
        if (is_null($val)) {
            return (float)0;
        }

        if (!is_scalar($val)) {
            throw new InvalidDataTypeException("Non-scalar value encountered when float is expected");
        }

        return floatval($val);
    }

    protected function requireString(&$val)
    {
        if (is_array($val)) {
            throw new InvalidDataTypeException("Array value is encountered when string is expected");
        }
        if (is_object($val) && !method_exists($val, "__toString")) {
            throw new InvalidDataTypeException(
                "Object doesn't support __toString() method when string value is expected"
            );
        }

        return strval($val);
    }

    protected function requireArray(&$val)
    {
        if (is_null($val)) {
            return [];
        }

        if (!is_array($val)) {
            throw new InvalidDataTypeException("Non array encountered when array is expected");
        }

        return $val;
    }

    protected function requireArray2D(&$val)
    {
        $arr = $this->requireArray($val);
        foreach ($arr as $k => &$v) {
            if (!is_array($v)) {
                throw new InvalidDataTypeException("Non array element encountered when 2D array is expected");
            }
        }

        return $arr;
    }

    protected function requireBool(&$val)
    {
        if (is_bool($val)) {
            return $val;
        }

        if (is_string($val)) {
            switch (strtolower($val)) {
                case "on":
                case "true":
                case "yes":
                    return true;
                    break;
                case "off":
                case "false":
                case "no":
                    return false;
                    break;
                default:
                    throw new InvalidDataTypeException("Unknown string encountered for bool val, string = $val");
                    break;
            }
        }

        return intval($val) ? true : false;
    }

    protected function requireObject(&$val)
    {
        if (is_object($val)) {
            return $val;
        }

        throw new InvalidDataTypeException("Value is not an object, value = " . print_r($val, true));
    }

    protected function requireMixed(&$val)
    {
        return $val;
    }
}
