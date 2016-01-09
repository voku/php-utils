<?php
/**
 * Created by PhpStorm.
 * User: minhao
 * Date: 2015-09-15
 * Time: 19:04
 */

use Oasis\Mlib\Utils\ArrayDataProvider;
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
            "array"        => [
                0,
                1,
                2,
            ],
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
            "a.x"          => "y",
        ];
        $this->dp = new ArrayDataProvider($data);
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->dp->getMandatory("int", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(2, $this->dp->getMandatory("float", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(0, $this->dp->getMandatory("string", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(1, $this->dp->getMandatory("bool", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(0, $this->dp->getMandatory("bool_str_on", ArrayDataProvider::INT_TYPE));

        $this->assertEquals(1, $this->dp->getMandatory("int", ArrayDataProvider::FLOAT_TYPE));
        $this->assertEquals(2.4, $this->dp->getMandatory("float", ArrayDataProvider::FLOAT_TYPE));
        $this->assertEquals(0, $this->dp->getMandatory("string", ArrayDataProvider::FLOAT_TYPE));
    }

    public function testCascadeGet()
    {
        $this->dp->setCascadeDelimiter(".");
        $this->assertEquals(55, $this->dp->getMandatory("a.b.c", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(33, $this->dp->getMandatory("a.b.d.g", ArrayDataProvider::INT_TYPE));
        $this->assertEquals(66, $this->dp->getMandatory("a.d.e", ArrayDataProvider::INT_TYPE));
        $this->assertEquals('y', $this->dp->getMandatory("a.x", ArrayDataProvider::STRING_TYPE));

        $this->setExpectedException(MandatoryValueMissingException::class);
        $this->dp->getMandatory('a.b.c.d');
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

    public function testOptionalNotExist()
    {
        $val = $this->dp->getOptional("java", ArrayDataProvider::STRING_TYPE, "bean");
        $this->assertEquals($val, "bean");
    }

    public function testOptionalWithoutDefault()
    {
        $val = $this->dp->getOptional("java", ArrayDataProvider::STRING_TYPE);
        $this->assertEquals($val, null);
        $this->assertTrue($val !== '');
    }

    public function testOptionalExist()
    {
        $this->assertEquals(true, $this->dp->getOptional("bool", ArrayDataProvider::BOOL_TYPE, false));
    }
}
