<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-05-16
 * Time: 15:28
 */

namespace Oasis\Mlib\Utils\Exceptions;

class DataValidationException extends \RuntimeException
{
    /**
     * @var string
     */
    protected $fieldName;

    public function __construct($fieldName, $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

}
