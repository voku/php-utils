<?php
use Oasis\Mlib\Utils\Exceptions\InvalidDataTypeException;
use Oasis\Mlib\Utils\Validators\BooleanValidator;

/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2016-09-02
 * Time: 15:59
 */
class BooleanValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getInvalidInputInStrictMode
     *
     * @param $target
     */
    public function testStrictModeInvalidInput($target)
    {
        $validator = new BooleanValidator(true);
        
        self::setExpectedException(InvalidDataTypeException::class);
        self::assertTrue(is_bool($validator->validate($target)));
    }
    
    /**
     * @dataProvider getValidInputInStrictMode
     *
     * @param $target
     */
    public function testStrictModeValid($target)
    {
        $validator = new BooleanValidator(true);
        
        self::assertTrue(is_bool($validator->validate($target)));
    }
    
    /**
     * @dataProvider getInvalidInputInNonStrictMode
     *
     * @param $target
     */
    public function testNonStrictModeInvalidInput($target)
    {
        $validator = new BooleanValidator(false);
        
        self::setExpectedException(InvalidDataTypeException::class);
        self::assertTrue(is_bool($validator->validate($target)));
    }
    
    /**
     * @dataProvider getValidInputInNonStrictMode
     *
     * @param $target
     */
    public function testNonStrictModeValid($target)
    {
        $validator = new BooleanValidator(false);
        
        self::assertTrue(is_bool($validator->validate($target)));
    }
    
    public function getInvalidInputInStrictMode()
    {
        return [
            [''],
            ['on'],
            ['1'],
            ['0.0'],
            [1],
            [0],
            [1.0],
            [0.0],
            [null],
            [new stdClass()],
            [[]],
            [[123]],
        ];
    }
    
    public function getInvalidInputInNonStrictMode()
    {
        return [
            [''],
            ['1.0'],
            ['0.0'],
            [new stdClass()],
            [[]],
            [[123]],
        ];
    }
    
    public function getValidInputInNonStrictMode()
    {
        return [
            [true],
            [false],
            ['on'],
            ['On'],
            ['ON'],
            ['off'],
            ['yes'],
            ['no'],
            ['true'],
            ['false'],
            [1],
            [0],
            [1.0],
            [0.0],
            [null],
        ];
    }
    
    public function getValidInputInStrictMode()
    {
        return [
            [true],
            [false],
        ];
    }
}