<?php
namespace Yilar;

/**
 * Represents a parsed @property annotation.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
class Property {
	/**
	 * The backing field for the properties.
	 *
	 * @var string[]
	 */
	private $_values = [];

	/**
	 * Construct a new Property instance.
	 *
	 * @param string $name   The name.
	 * @param string $access The access level. (read, write or the empty string for both)
	 * @param string $type   The type.
	 */
	public function __construct($name, $access, $type) {
		$this->_values['name']   = $name;
		$this->_values['access'] = $access;
		$this->_values['type']   = $type;
	}
	
	/**
	 * Magic method which provides read functionality to the name, access and type properties.
	 *
	 * @param string $name The name of the property to get.
	 *
	 * @return string The value of the property.
	 */
	public function __get($name) {
		if (!isset($this->_values[$name])) {
			throw new Yilar\Exception(__CLASS__ . " has no property named $name.");
		} else {
			return $this->_values[$name];
		}
	}
}
