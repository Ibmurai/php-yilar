<?php
namespace Yilar\Test;

/**
 * Class for testing more property types.
 *
 * @property object           $object      An object.
 * @property \Yilar\Test\User $user        An instance of the User class.
 * @property resource         $resource    Some resource.
 * @property array            $array       Just an array.
 * @property mixed            $whatevs     Stuff.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class MoreTypes {
	use \Yilar\Traits\Properties;
}
