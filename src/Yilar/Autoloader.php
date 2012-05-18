<?php
namespace Yilar;

require_once __DIR__ . '/Traits/Singleton.php';

class Autoloader {
	/** @var string The base path to autoload from. */
	private $_path;

	use Traits\Singleton {
		getInstance as private;
	}

	private function __construct() {
		$this->_path = realpath(__DIR__ . '/../') . '/';
	}

	public static function register() {
		spl_autoload_register(array(self::getInstance(), 'autoload'));
	}

	private function autoload($name) {
		$file = $this->_path . str_replace('\\', '/', $name) . '.php';
		if (file_exists($file)) {
			require $file;
		}
		
		var_dump($file);
	}
}
