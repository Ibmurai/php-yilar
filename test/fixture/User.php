<?php
namespace Yilar\Test;

/**
 * A class for testing the use of the Yilar\Traits\Properties trait.
 *
 * @property-read  integer $id            The id of the user.
 * @property-write string  $writeOnly     Something which can only be set and not got...
 * @property       string  $name          The name of the user.
 * @property       boolean $hetero        True if the user is hetero sexual.
 * @property       integer $age           The age, in years, of the user.
 * @property       float   $height        The height, in centimeters, of the user.
 * @property       float[] $fingerLengths The length, in centimeters, of the users fingers. :S
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
class User {
	use \Yilar\Traits\Properties;

	/**
	 * @param integer $id The id of the user.
	 */
	public function __construct($id) {
		$this->id = $id;
	}

	/**
	 * A helper to provide a calling context for the testIsSuperPrivate test.
	 *
	 * @return boolean
	 */
	public function helperTestIsPrivate() {
		$notSoPrivate = new \Yilar\Test\ScopeAnalyzer();
		return $notSoPrivate->helperTestIsPrivate();
	}
}
