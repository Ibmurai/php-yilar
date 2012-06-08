<?php
namespace Yilar;

/**
 * A singleton for parsing class docblocks, for their @property-annotations.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
class DocblockParser {
	use Traits\Singleton;
	
	/**
	 * Parse the given docblock.
	 *
	 * @param string $docblock The docblock to parse.
	 *
	 * @return Yilar\Property[] An array of the annotated properties.
	 */
	public function parse($docblock) {
		preg_match_all('/@property(?:-(?<access>read|write))?\s+(?:(?<type>[^\s]+)\s+)\$(?<name>[^\s]+)/ms', $docblock, $matches);
		
		$result = [];
		
		$i = 0;
		foreach ($matches['name'] as $name) {
			$result[] = new Property($name, $matches['access'][$i], $matches['type'][$i]);
			
			$i++;
		}
		
		return $result;
	}
	
	/**
	 * Get the docblock for a given class.
	 *
	 * @return string The docblock.
	 */
	public function getDocblock($className) {
		$reflection = new \ReflectionClass($className);
		
		return $reflection->getDocComment();
	}
}
