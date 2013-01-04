<?php
namespace Yilar\Traits;

use \Yilar\VM;
use \Yilar\Exception;
use \Yilar\ScopeAnalyzer;

/**
 * This trait will make all properties defined in the using class' docblock available and typesafe.
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
	 * @throws Exception If you attempt to access an undefined property or the property is not readable from the scope.
	 */
	public function __get($name) {
		$vm       = VM::getInstance();
		$property = $vm->getProperty($this, $name);

		if ($property->access === 'write' && !ScopeAnalyzer::isPrivate($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2))) {
			throw new Exception("Property, {$property->name}, is write only.");
		}

		return $vm->getValue($this, $property);
	}

	/**
	 * PHP magic __set method to set properties.
	 *
	 * @param string $name  The name of the property to set.
	 * @param mixed  $value The value to set.
	 *
	 * @throws Exception If you attempt to access an undefined property or the property is not writable from the scope.
	 */
	public function __set($name, $value) {
		$vm       = VM::getInstance();
		$property = $vm->getProperty($this, $name);

		if ($property->access === 'read' && !ScopeAnalyzer::isPrivate($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2))) {
			throw new Exception("Property, {$property->name}, is read only.");
		}

		$vm->setValue($this, $property, $value);
	}

	/**
	 * PHP magic __unset method to unset properties.
	 *
	 * @param string $name The name of the property to unset.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __unset($name) {
		$vm       = VM::getInstance();
		$property = $vm->getProperty($this, $name);

		if ($property->access === 'read' && !ScopeAnalyzer::isPrivate($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2))) {
			throw new Exception("Property, {$property->name}, is read only.");
		}

		$vm->setValue($this, $property, null);
	}

	/**
	 * PHP magic __isset method to check if properties are set using the isset() "function".
	 *
	 * @param string $name The name of the property to test.
	 *
	 * @return boolean True if the property is not null.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __isset($name) {
		$vm       = VM::getInstance();
		$property = $vm->getProperty($this, $name);

		if ($property->access === 'write' && !ScopeAnalyzer::isPrivate($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2))) {
			throw new Exception("Property, {$property->name}, is write only.");
		}

		return $vm->getValue($this, $property) !== null;
	}

	/**
	 * PHP magic __destruct method, to tell the vm clear the property values it holds.
	 */
	final public function __destruct() {
		$vm = VM::getInstance();

		$vm->clearValues($this);
	}
}
