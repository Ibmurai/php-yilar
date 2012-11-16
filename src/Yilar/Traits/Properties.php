<?php
namespace Yilar\Traits;

use \Yilar\VM;

/**
 * This trait will make all @property declarations on a class accessible and type safe.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
trait Properties {
	/**
	 * Get a hashed array of all properties and values.
	 *
	 * @return array
	 */
	public function toArray() {
		$vm = VM::getInstance();

		$res = [];
		foreach ($vm->getProperties($this) as $property) {
			$res[$property->name] = $vm->getValue($this, $property);
		}

		return $res;
	}

	/**
	 * PHP magic __get method to get properties.
	 *
	 * @param string $name The name of the property to get.
	 *
	 * @return mixed The property value.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __get($name) {
		$vm = VM::getInstance();

		return $vm->getValue($this, $vm->getProperty($this, $name));
	}

	/**
	 * PHP magic __set method to set properties.
	 *
	 * @param string $name The name of the property to set.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __set($name, $value) {
		$vm = VM::getInstance();

		$vm->setValue($this, $vm->getProperty($this, $name), $value);
	}

	/**
	 * PHP magic __unset method to unset properties.
	 *
	 * @param string $name The name of the property to unset.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __unset($name) {
		$vm = VM::getInstance();

		$vm->setValue($this, $vm->getProperty($this, $name), null);
	}

	/**
	 * PHP magic __isset method to check if properties are set using the isset() "function".
	 *
	 * @param string $name The name of the property to test.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __isset($name) {
		$vm = VM::getInstance();

		return $vm->getValue($this, $vm->getProperty($this, $name)) !== null;
	}
}
