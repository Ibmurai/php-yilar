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
	 * @return object
	 */
	public function getInstance() {
		static $instance;
		
		if (!isset($instance)) {
			$instance = new self();
		}
		
		return $instance;
	}
	
	/**
	 * Make the constructor private.
	 *
	 * @return void
	 */
	private function __construct() {
	}
}
