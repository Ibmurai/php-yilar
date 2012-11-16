<?php
namespace Yilar;

require_once __DIR__ . '/Traits/Singleton.php';

/**
 * The Yilar autoloader.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
class Autoloader {
	/** @var string The base path to autoload from. */
	private $_path;

	/**
	 * Use the singleton trait, with getInstance as private.
	 */
	use Traits\Singleton {
		getInstance as private;
	}

	/**
	 * Overridden constructor, to determine the base autoloading path.
	 */
	private function __construct() {
		$this->_path = realpath(__DIR__ . '/../') . '/';
	}

	/**
	 * Register the autoloader.
	 */
	public static function register() {
		spl_autoload_register(array(self::getInstance(), 'autoload'));
	}

	/**
	 * Unregister the autoloader.
	 */
	public static function unRegister() {
		spl_autoload_unregister(array(self::getInstance(), 'autoload'));
	}

	/**
	 * The autoloading function.
	 *
	 * @param string $name The name of the class the autoloader should look for.
	 */
	private function autoload($name) {
		$file = $this->_path . str_replace('\\', '/', $name) . '.php';
		if (file_exists($file)) {
			require $file;
		}
	}
}
