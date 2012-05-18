<?php
require_once __DIR__ . '/../src/Yilar/Autoloader.php';
use Yilar\Autoloader;
/**
 * The base for all Yilar test cases.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase {
	/**
	 * Setup registers the autoloader.
	 *
	 * @return null
	 */
	protected function setUp() {
		Autoloader::register();
	}
	
	/**
	 * Teardown unregisters the autoloader.
	 *
	 * @return null
	 */
	protected function tearDown() {
		Autoloader::unRegister();
	}
}
