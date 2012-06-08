<?php
namespace Yilar\Test;
require_once __DIR__ . '/../../src/Yilar/Autoloader.php';
/**
 * The base for all Yilar test cases.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase {
	/**
	 * Register the Yilar autoloader, before running any tests.
	 *
	 * @return null
	 */
	public static function setUpBeforeClass() {
		\Yilar\Autoloader::register();
	}

	/**
	 * Teardown unregisters the autoloader.
	 *
	 * @return null
	 */
	public static function tearDownAfterClass() {
		\Yilar\Autoloader::unRegister();
	}
}
