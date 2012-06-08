<?php
namespace Yilar\Test;
require_once __DIR__ . '/../../src/Yilar/Autoloader.php';
use Yilar;
/**
 * Test the docblock parser.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 *
 * @runInSeparateProcess
 */
class DocblockParser extends \PHPUnit_Framework_TestCase {
	/**
	 * Register the Yilar autoloader, before running any tests.
	 *
	 * @return null
	 */
	public static function setUpBeforeClass() {
		Yilar\Autoloader::register();
	}

	/**
	 * @covers Yilar\DocblockParser::getInstance
	 *
	 * @return null
	 */	
	public function testGetInstance() {
		$this->assertInstanceOf('Yilar\DocblockParser', Yilar\DocblockParser::getInstance());
	}
	
	/**
	 * @param string The docblock to run the test on.
	 * @param array  The expected values of name, access and type in that order.
	 *
	 * @dataProvider providerDocblock
	 * @covers       Yilar\DocblockParser::parse
	 *
	 * @return null
	 */
	public function testParseDocblock($docblock, array $expected) {
		$properties = Yilar\DocblockParser::getInstance()->parse($docblock);
	
		$this->assertInternalType('array', $properties);
		
		$i = 0;
		foreach($properties as $property) {
			$this->assertInstanceOf('Yilar\Property', $property);
			$this->assertEquals($expected[$i][0], $property->name);
			$this->assertEquals($expected[$i][1], $property->access);
			$this->assertEquals($expected[$i][2], $property->type);
			$i++;
		}
	}
	
	/**
	 * Provides test data for ::testGetDocblock.
	 *
	 * @return array[] An array of arrays of docblocks, and expected parsed values.
	 */
	public function providerDocblock() {
		return [
			['/** @property string  $lol */', [['lol', '', 'string']]],
			['/** @property integer $inty */', [['inty', '', 'integer']]],
			['/** @property boolean $bool */', [['bool', '', 'boolean']]],
			['/** @property array   $arr */' , [['arr', '', 'array']]],
			['/**
			   * @property       array   $arr
			   * @property       string  $banana
			   * @property-read  integer $id
			   * @property-write string  $writeMe
			   *
			   * @author Mistah Lawls <lol@lol.lol>
			   */',
				[
					['arr', '', 'array'], 
					['banana', '', 'string'],
					['id', 'read', 'integer'],
					['writeMe', 'write', 'string'],
				]
			],
		];
	}
	
	/**
	 * @dataProvider providerClassNames
	 * @covers       Yilar\DocblockParser::getDocblock
	 *
	 * @return null
	 */
	public function testGetDocblock($class) {
		$docblock = Yilar\DocblockParser::getInstance()->getDocblock($class);
		
		$this->assertThat(
			$docblock,
			$this->logicalOr(
				$this->isType('string'),
				$this->equalTo(false)
			)
		);
	}
	
	/**
	 * Provides test data for ::testGetDocblock.
	 *
	 * @return array[] An array of arrays containing one string which is a class name.
	 */
	public function providerClassNames() {
		return [
			['Yilar\Test\DocblockParser'],
			['Yilar\DocblockParser'],
			['Yilar\Property'],
			['\Exception'],
			['Exception'],
			['ReflectionClass'],
		];
	}
}
