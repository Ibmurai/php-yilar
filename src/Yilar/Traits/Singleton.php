<?php
namespace Yilar\Traits;

/**
 * A generic singleton trait.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
trait Singleton {
	/**
	 * Get the singleton instance.
	 *
	 * @return static
	 */
	final public static function getInstance() {
		static $instance = null;

		if (!isset($instance)) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Make the constructor private.
	 *
	 * @return void
	 */
	private function __construct() {}
}
