<?php
namespace Yilar\Traits;

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
