<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-15
 * Time: 11:48
 */
namespace Oasis\Mlib\Utils;

use Oasis\Mlib\Utils\Exceptions\InvalidDataTypeException;
use Oasis\Mlib\Utils\Exceptions\InvalidValueException;
use Oasis\Mlib\Utils\Exceptions\MandatoryValueMissingException;

abstract class AbstractDataProvider implements DataProviderInterface
{
    use DataProviderTypeParserTrait;

    /**
     * @param string     $key
     * @param string     $type
     * @param bool|false $isMandatory
     * @param null       $default
     *
     * @return mixed
     * @throws InvalidDataTypeException
     * @throws MandatoryValueMissingException
     */
    public function get($key, $type = self::STRING_TYPE, $isMandatory = false, $default = null)
    {
        $value = $this->getValue($key);

        if ($value === null) {
            if ($isMandatory) {
                throw new MandatoryValueMissingException($key, "Mandatory value $key is missing in data");
            }
            else {
                return $default;
            }
        }
        else {

            if (!in_array(
                $type,
                [
                    self::INT_TYPE,
                    self::FLOAT_TYPE,
                    self::STRING_TYPE,
                    self::ARRAY_TYPE,
                    self::ARRAY_2D_TYPE,
                    self::BOOL_TYPE,
                    self::OBJECT_TYPE,
                    self::MIXED_TYPE,
                ]
            )
            ) {
                throw new InvalidDataTypeException($key, "For key <$key>, tyype '$type' is not an allowed type");
            }

            try {
                return $this->parseValue($type, $value);
            } catch (InvalidValueException $e) {
                throw new InvalidDataTypeException($key, "Invalid value for key <$key>: " . $e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    public function getMandatory($key, $type = self::STRING_TYPE)
    {
        return $this->get($key, $type, true);
    }

    public function getOptional($key, $type = self::STRING_TYPE, $default = null)
    {
        return $this->get($key, $type, false, $default);
    }

    /**
     * @param string $key the key to be used to read a value from the data provider
     *
     * @return mixed|null       null indicates the value is not presented in the data provider
     */
    abstract protected function getValue($key);

}
