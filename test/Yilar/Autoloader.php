<?php
namespace Yilar\Test;
require_once __DIR__ . '/../../src/Yilar/Autoloader.php';
use Yilar;
/**
 * Test the autoloader.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 *
 * @runTestsInSeparateProcesses
 */
class Autoloader extends \PHPUnit_Framework_TestCase {
	/**
	 * Test autoloader registration.
	 */
	public function testRegister() {
		$before = count(spl_autoload_functions());
		Yilar\Autoloader::register();
		$after = count(spl_autoload_functions());
		$this->assertEquals($before + 1, $after);
	}
	
	/**
	 * Test autoloader unregistration.
	 */
	public function testUnregister() {
		Yilar\Autoloader::register();
		$before = count(spl_autoload_functions());
		Yilar\Autoloader::unRegister();
		$after = count(spl_autoload_functions());
		$this->assertEquals($before - 1, $after);
	}
}
