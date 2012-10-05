<?php
namespace Yilar\Traits;

/**
 * This trait will make all @property declarations on a class accessible and type safe.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
trait Properties {
	/**
	 * This will be called once to parse the docblock, and determine the properties of the class.
	 *
	 * @return null
	 */
	private function _getProperty($name, $full = false) {
		static $properties = null;

		if ($full) return $properties;

		if ($properties === null) {
			$properties = \Yilar\DocblockParser::getInstance()->parseClass(__CLASS__);
		}

		if (isset($properties[$name])) {
			return $properties[$name];
		} else {
			throw new Exception("No property named, $name, is defined.");
		}
	}

	/**
	 * Gets or sets the property named $name. If $value is null the value of the $name
	 * property will be returned.
	 *
	 * @staticvar array $values The internal array used to store the values.
	 *
	 * @param string $name  The name of the property to set.
	 * @param mixed  $value The value to set the property to - null to get the property instead.
	 *
	 * @return mixed|null
	 *
	 * @todo $value = null is not a good default... I need something else there, to allow for nullable properties.
	 * @todo Add type safety checks.
	 */
	private function _getOrSetValue($name, $value = null) {
		static $values = [];

		if (!isset($values[spl_object_hash($this)])) {
			$values[spl_object_hash($this)] = [];
		}

		$vals = &$values[spl_object_hash($this)];

		if ($value === null) {
			return isset($vals[$name]) ? $vals[$name] : null;
		} else {
			$vals[$name] = $value;
		}

		return null;
	}

	/**
	 * Get a hashed array of all properties and values.
	 *
	 * @return array
	 */
	public function toArray() {
		$res = [];
		
		foreach ($this->_getProperty(null, true) as $prop) {
			$res[$prop->name] = $this->_getOrSetValue($prop->name);
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
		$property = $this->_getProperty($name);

		if ($property->access != 'write' || debug_backtrace()[1]['class'] === __CLASS__) {
			return $this->_getOrSetValue($name);
		} else {
			throw new Exception("The property, $name, is not readable.");
		}
	}

	/**
	 * PHP magic __set method to set properties.
	 *
	 * @param string $name The name of the property to set.
	 *
	 * @return mixed The property value.
	 *
	 * @throws Exception If you attempt to access an undefined property.
	 */
	public function __set($name, $value) {
		$property = $this->_getProperty($name);

		if ($property->access != 'read' || debug_backtrace()[1]['class'] === __CLASS__) {
			return $this->_getOrSetValue($name, $value);
		} else {
			throw new Exception("The property, $name, is not writable.");
		}
	}

	/* TODO __isset and __unset */
}
