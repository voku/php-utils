<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-15
 * Time: 19:04
 */

use Oasis\Mlib\Utils\ArrayDataProvider;
use Oasis\Mlib\Utils\Exceptions\DataEmptyException;
use Oasis\Mlib\Utils\Exceptions\InvalidDataTypeException;
use Oasis\Mlib\Utils\Exceptions\MandatoryValueMissingException;

class MlibDataProviderTest extends PHPUnit_Framework_TestCase
{
    /** @var ArrayDataProvider */
    protected $dp = null;
    
    protected function setUp()
    {
        $data     = [
            "int"          => 1,
            "float"        => 2.4,
            "string"       => "name",
            "empty"        => "",
            "array"        => [
                0,
                1,
                2,
            ],
            "null"         => null,
            "object"       => new \stdClass(),
            "bool"         => true,
            "bool_str_on"  => "on",
            "bool_str_off" => "off",
            "a"            => [
                "b"   => [
                    "c" => 55,
                    "d" => [
                        "g" => 33,
                    ],
                ],
                "d.e" => 66,
                "d"   => [
                    "e" => 77,
                ],
            ],
            "2darray"      => [
                [1, 2],
                [3, 4],
                [5, 6],
            ],
            "a.x"          => "y",
        
        ];
        $this->dp = new ArrayDataProvider($data);
    }
    
    public function testHas()
    {
        self::assertTrue($this->dp->has('int'));
        self::assertTrue($this->dp->has('int', ArrayDataProvider::INT_TYPE));
        self::assertTrue($this->dp->has('float', ArrayDataProvider::FLOAT_TYPE));
        self::assertTrue($this->dp->has('string', ArrayDataProvider::STRING_TYPE));
        self::assertTrue($this->dp->has('empty', ArrayDataProvider::STRING_TYPE));
        self::assertTrue($this->dp->has('array'));
        self::assertTrue($this->dp->has('array', ArrayDataProvider::ARRAY_TYPE));
        self::assertTrue($this->dp->has('object'));
        self::assertTrue($this->dp->has('object', ArrayDataProvider::OBJECT_TYPE));
    }
    
    public function testGet()
    {
        self::assertEquals(1, $this->dp->getMandatory("int", ArrayDataProvider::INT_TYPE));
        self::assertEquals(1, $this->dp->getMandatory("int", ArrayDataProvider::FLOAT_TYPE));
        self::assertEquals(2.4, $this->dp->getMandatory("float", ArrayDataProvider::FLOAT_TYPE));
        self::assertEquals('name', $this->dp->getMandatory("string", ArrayDataProvider::STRING_TYPE));
        self::assertEquals(true, $this->dp->getMandatory("bool", ArrayDataProvider::BOOL_TYPE));
        self::assertEquals(true, $this->dp->getMandatory("bool_str_on", ArrayDataProvider::BOOL_TYPE));
        
        self::assertInstanceOf(
            \stdClass::class,
            $this->dp->getMandatory("object", ArrayDataProvider::OBJECT_TYPE)
        );
        self::assertNotEquals(0, $this->dp->getMandatory("string", ArrayDataProvider::MIXED_TYPE));
        self::assertEquals('name', $this->dp->getMandatory("string", ArrayDataProvider::MIXED_TYPE));
    }
    
    /**
     * @dataProvider
     */
    public function testNull()
    {
        self::setExpectedException(MandatoryValueMissingException::class);
        $this->dp->getMandatory('null', ArrayDataProvider::INT_TYPE);
    }
    
    public function getValidatorsForNullTest()
    {
        return [
            [ArrayDataProvider::INT_TYPE],
            [ArrayDataProvider::FLOAT_TYPE],
            [ArrayDataProvider::STRING_TYPE],
            [ArrayDataProvider::BOOL_TYPE],
            [ArrayDataProvider::ARRAY_TYPE],
            [ArrayDataProvider::MIXED_TYPE],
        ];
    }
    
    public function testNonEmpytString()
    {
        self::assertEquals('', $this->dp->getMandatory('empty', ArrayDataProvider::STRING_TYPE));
        self::setExpectedException(DataEmptyException::class);
        $this->dp->getMandatory('empty', ArrayDataProvider::NON_EMPTY_STRING_TYPE);
    }
    
    public function testHierarchicalGet()
    {
        self::assertEquals(55, $this->dp->getMandatory("a.b.c", ArrayDataProvider::INT_TYPE));
        self::assertEquals(33, $this->dp->getMandatory("a.b.d.g", ArrayDataProvider::INT_TYPE));
        self::assertEquals(66, $this->dp->getMandatory("a.d.e", ArrayDataProvider::INT_TYPE));
        self::assertEquals('y', $this->dp->getMandatory("a.x", ArrayDataProvider::STRING_TYPE));
        
        $this->setExpectedException(MandatoryValueMissingException::class);
        $this->dp->getMandatory('a.b.c.d');
    }
    
    public function testPathPushPop()
    {
        $this->dp->pushPath('a');
        self::assertTrue(is_array($this->dp->getMandatory('b', ArrayDataProvider::ARRAY_TYPE)));
        self::assertEquals(55, $this->dp->getMandatory('b.c', ArrayDataProvider::INT_TYPE));
        $this->dp->pushPath('b');
        self::assertEquals(55, $this->dp->getMandatory('c', ArrayDataProvider::INT_TYPE));
        self::assertEquals(33, $this->dp->getMandatory('d.g', ArrayDataProvider::INT_TYPE));
        
        $this->dp->popPath();
        self::assertEquals(66, $this->dp->getMandatory("d.e", ArrayDataProvider::INT_TYPE));
        $this->dp->pushPath('d');
        self::assertEquals(77, $this->dp->getMandatory("e", ArrayDataProvider::INT_TYPE));
        
        $this->dp->setCurrentPath('');
        self::assertEquals(66, $this->dp->getMandatory('a.d.e', ArrayDataProvider::INT_TYPE));
    }
    
    public function test2DArrayGet()
    {
        $a = $this->dp->getMandatory('2darray', ArrayDataProvider::ARRAY_2D_TYPE);
        self::assertTrue(is_array($a));
        foreach ($a as $idx => $val) {
            self::assertTrue(is_array($val), "for 'a', value at #$idx is not array, value = " . json_encode($val));
        }
    }
    
    public function testInvalidDataTypeExpectingArray()
    {
        $this->dp->getMandatory('int', ArrayDataProvider::INT_TYPE);
        
        $this->setExpectedException(InvalidDataTypeException::class);
        $this->dp->getMandatory('int', ArrayDataProvider::ARRAY_TYPE);
    }
    
    public function testInvalidDataTypeExpectingNotArray()
    {
        $this->dp->getMandatory('array', ArrayDataProvider::ARRAY_TYPE);
        
        $this->setExpectedException(InvalidDataTypeException::class);
        $this->dp->getMandatory('array', ArrayDataProvider::INT_TYPE);
    }
    
    public function testMandatoryOk()
    {
        $this->dp->getMandatory("int");
    }
    
    public function testMandatoryNotExist()
    {
        $this->setExpectedException(MandatoryValueMissingException::class);
        $this->dp->getMandatory("java");
    }
    
    public function testMandatoryValueMissingWithKey()
    {
        try {
            $this->dp->getMandatory("java");
        } catch (MandatoryValueMissingException $e) {
            self::assertEquals('java', $e->getFieldName());
        }
    }
    
    public function testOptionalNotExist()
    {
        $val = $this->dp->getOptional("java", ArrayDataProvider::STRING_TYPE, "bean");
        self::assertEquals($val, "bean");
    }
    
    public function testOptionalWithoutDefault()
    {
        $val = $this->dp->getOptional("java", ArrayDataProvider::STRING_TYPE);
        self::assertEquals($val, null);
        self::assertTrue($val !== '');
    }
    
    public function testOptionalExist()
    {
        self::assertEquals(true, $this->dp->getOptional("bool", ArrayDataProvider::BOOL_TYPE, false));
    }
}
