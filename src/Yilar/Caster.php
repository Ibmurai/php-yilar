<?php
namespace Yilar;

/**
 * Handles type casting of the different supported property types.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class Caster {
	use Traits\Singleton;

	/**
	 * Cast the given value to the given type.
	 *
	 * Types are a subset of phpDocumentor 2's ABNF:
	 * Classnames and the following keywords are supported:
	 * "string"|"integer"|"int"|"boolean"|"bool"|"float"|"double"|"object"|"mixed"|"array"|"resource"
	 * Additionally, single dimensional arrays of the above are supported.
	 *
	 * Examples of supported types:
	 * "SomeClass", "int", "integer", "array", "string[]", "mixed"...
	 *
	 * Examples of unsupported types:
	 * "array|bool", "(string[])[]", "(Horse|Rabbit)[]", ...
	 * 
	 * @link http://www.phpdoc.org/docs/latest/for-users/types.html#abnf
	 *
	 * @param string $type
	 * @param mixed  $value
	 *
	 * @throws CastingException If the value cannot be cast as the given type. Or the type is bogus.
	 */
	public function cast($type, $value) {
		// All types are nullable.
		if ($value === null) {
			return null;
		}

		// Arrays of something.
		if (substr($type, -2) == '[]') {
			$arr = $this->_castAsArray($value);

			foreach ($arr as &$val) {
				$val = $this->cast(substr($type, 0, -2), $val);
			}

			return $val;
		}

		// Keywords and class names
		switch ($type) {
			case 'string':
				return $this->_castAsString($value);
			case 'integer':
			case 'int':
				return $this->_castAsInteger($value);
			case 'boolean':
			case 'bool':
				return $this->_castAsBoolean($value);
			case 'float':
			case 'double':
				return $this->_castAsFloat($value);
			case 'object':
				return $this->_castAsObject($value);
			case 'array':
				return $this->_castAsArray($value);
			case 'resource':
				return $this->_castAsResource($value);
			case 'mixed':
				return $value;
			default:
				return $this->_castAsClassInstance($type, $value);
		}
	}

	/**
	 * Attempt to cast the given value as an integer.
	 * Will succesfully cast:
	 *  * Actual integers
	 *  * Strings containing only digits
	 *  * Boolean values - true and false are cast to 1 and 0 respectively.
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return integer The value cast as an integer.
	 *
	 * @throws CastingException If the value cannot be cast as integer.
	 */
	private function _castAsInteger($value) {
		if (is_integer($value)) {
			return $value;
		} else if (is_string($value) && preg_match('/^[0-9]+$/', $value)) {
			return (integer)$value;
		} else if (is_bool($value)) {
			return $value ? 1 : 0;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as integer.');
		}
	}

	/**
	 * Attempt to cast the given value as a string.
	 * Will succesfully cast:
	 *  * Actual strings
	 *  * Integers
	 *  * Floats
	 *  * Instances of classes implementing __toString
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return string The value cast as a string.
	 *
	 * @throws CastingException If the value cannot be cast as string.
	 */
	private function _castAsString($value) {
		if (is_string($value) || is_integer($value) || (is_object($value) && method_exists($value, '__toString'))) {
			return (string)$value;
		} else if (is_float($value)) {
			if ($value - floor($value) === 0.0) {
				return sprintf('%.1f', $value);
			} else {
				return (string)$value;
			}
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as string.');
		}
	}

	/**
	 * Attempt to cast the given value as an array.
	 * Will succesfully cast:
	 *  * Actual arrays
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return array The value cast as an array.
	 *
	 * @throws CastingException If the value cannot be cast as an array.
	 */
	private function _castAsArray($value) {
		if (is_array($value)) {
			return $value;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as an array.');
		}
	}

	/**
	 * Attempt to cast the given value as a boolean.
	 * Will succesfully cast:
	 *  * Actual booleans
	 *  * Integers (0 => false, everything else => true)
	 *  * Strings ('' => false, everything else => true)
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return boolean The value cast as a boolean.
	 *
	 * @throws CastingException If the value cannot be cast as boolean.
	 */
	private function _castAsBoolean($value) {
		if (is_bool($value)) {
			return $value;
		} else if (is_integer($value)) {
			return $value === 0 ? false : true;
		} else if (is_string($value)) {
			return $value === '' ? false : true;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as boolean.');
		}
	}

	/**
	 * Attempt to cast the given value as a float.
	 * Will succesfully cast:
	 *  * Actual floats
	 *  * Integers
	 *  * Strings of the example forms: "0.001", "10.42", "42", ".42" (only dot is supported as
	 *    decimal seperator)
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return float The value cast as a float.
	 *
	 * @throws CastingException If the value cannot be cast as a float.
	 */
	private function _castAsFloat($value) {
		if (is_float($value)) {
			return $value;
		} else if (is_integer($value)) {
			return (float)$value;
		} else if (is_string($value) && preg_match('/^[0-9]*\.?[0-9]+$/', $value)) {
			return (float)$value;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as float.');
		}
	}

	/**
	 * Attempt to cast the given value as an object.
	 * Will succesfully cast:
	 *  * Actual objects
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return object The value itself.
	 *
	 * @throws CastingException If the value cannot be cast as an object.
	 */
	private function _castAsObject($value) {
		if (is_object($value)) {
			return $value;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as object.');
		}
	}

	/**
	 * Attempt to cast the given value as a resource.
	 * Will succesfully cast:
	 *  * Actual resources
	 * All other values will throw an exception.
	 *
	 * @param mixed $value
	 *
	 * @return mixed The value itself.
	 *
	 * @throws CastingException If the value cannot be cast as a resource.
	 */
	private function _castAsResource($value) {
		if (is_resource($value)) {
			return $value;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ', could not be cast as resource.');
		}
	}

	/**
	 * Attempt to cast the given value as a an instance of a class.
	 * Will succesfully cast:
	 *  * Actual class instances of the given class or a child class.
	 * All other values will throw an exception.
	 *
	 * @param string $type  The name of the class to cast as.
	 * @param mixed  $value
	 *
	 * @return object The value itself.
	 *
	 * @throws CastingException If the value cannot be cast as an instance of the given class or a
	 *                          child class.
	 */
	private function _castAsClassInstance($class, $value) {
		if ($value instanceof $class) {
			return $value;
		} else {
			throw new CastingException('Value of type, ' . gettype($value) . ", could not be cast as $class.");
		}
	}
}
