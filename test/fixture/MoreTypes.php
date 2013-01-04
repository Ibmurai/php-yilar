<?php
namespace Yilar\Test;

/**
 * Class for testing more property types.
 *
 * @property object   $object   An object.
 * @property User     $user     An instance of the User class.
 * @property resource $resource Some resource.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class MoreTypes {
	use \Yilar\Traits\Properties;
}
