<?php
namespace Yilar;

/**
 * Don't worry about it.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class VM {
	use Traits\Singleton;

	/** @var Property[][] An array of ClassName => Property[] pairs. */
	private $_properties;

	/** @var array[] An array of object_hash => array pairs, where array is an array of property_name => value pairs. */
	private $_values;

	/**
	 * This will be called to determine the properties of the class.
	 *
	 * @param object $object An instance of an object.
	 * @param string $name   The name of the property to get.
	 *
	 * @return Property
	 *
	 * @throws Exception If an undefined property is requested.
	 */
	public function getProperty($object, $name) {
		$key = get_class($object);

		if (!isset($this->_properties[$key])) {
			$this->_properties[$key] = DocblockParser::getInstance()->parseClass($key);
		}

		if (isset($this->_properties[$key][$name])) {
			return $this->_properties[$key][$name];
		} else {
			throw new Exception("No Yilar property named, $name, is defined, for the class, $key.");
		}
	}

	/**
	 * Get all defined properties for a given instance
	 *
	 * @param object $object An instance of an object.
	 * @param string $name   The name of the property to get.
	 *
	 * @return Property
	 */
	public function getProperties($object) {
		$key = get_class($object);

		if (!isset($this->_properties[$key])) {
			$this->_properties[$key] = DocblockParser::getInstance()->parseClass($key);
		}

		return $this->_properties[$key];
	}

	/**
	 * Get the value of a property, for a given object.
	 *
	 * @param object   $object   The object to get a property value from.
	 * @param Property $property The property to get the value of.
	 *
	 * @return mixed The value of the property.
	 *
	 * @todo Enforce write only!
	 */
	public function getValue($object, Property $property) {
		$key = spl_object_hash($object);

		if (isset($this->_values[$key][$property->name])) {
			return $this->_values[$key][$property->name];
		} else {
			return null;
		}
	}

	/**
	 * Set the value of a property, for a given object.
	 *
	 * @param object   $object   The object to set a property value for.
	 * @param Property $property The property to set the value of.
	 * @param mixed    $value    The value to set.
	 *
	 * @throws CastingException If the value cannot be cast as the given type. Or the type is bogus.
	 *
	 * @todo Enforce read only!
	 */
	public function setValue($object, Property $property, $value) {
		$key = spl_object_hash($object);

		if (!isset($this->_values[$key])) {
			$this->_values[$key] = [];
		}

		$this->_values[$key][$property->name] = Caster::getInstance()->cast($property->type, $value);
	}
}
