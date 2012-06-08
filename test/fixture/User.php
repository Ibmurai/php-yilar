<?php
namespace Yilar\Test;

/**
 * A class for testing the use of the Yilar\Traits\Properties trait.
 *
 * @property-read  integer $id        The id of the user.
 * @property-write string  $writeOnly Something which can only be set and not got...
 * @property       string  $name      The name of the user.
 * @property       boolean $hetero    True if the user is hetero sexual.
 * @property       integer $age       The age, in years, of the user.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 */
class User {
	public function __construct($id) {
		$this->id = $id;
	}

	use \Yilar\Traits\Properties;
}

class Lol {
	use \Yilar\Traits\Properties;
}