<?php

namespace Yilar\Test;

require_once __DIR__ . '/../BaseTest.php';
/**
 * Test the docblock parser.
 *
 * @author Jens Riisom Schultz <ibber_of_crew42@hotmail.com>
 *
 */
class Properties extends BaseTest {
	/**
	 * Test standard, legal, getting and setting of properties.
	 */
	public function testGetSetPropertyValue() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user1 = new User(4242);
		$this->assertSame(4242, $user1->id);

		$user1->name = 'Horse';
		$this->assertSame('Horse', $user1->name);

		$user1->age = 42;
		$this->assertSame(42, $user1->age);

		$user1->fingerLengths = [0.1, 0.2, 0.3, 0.4, 0.1];
		$this->assertSame([0.1, 0.2, 0.3, 0.4, 0.1], $user1->fingerLengths);

		$user1->height = 42.11;
		$this->assertSame(42.11, $user1->height);

		$user1->hetero = false;
		$this->assertSame(false, $user1->hetero);

		$user2 = new User(21);
		$this->assertSame(21, $user2->id);

		$user2->name = 'Giraffe';
		$this->assertSame('Giraffe', $user2->name);

		$user2->age = '4';
		$this->assertSame(4, $user2->age);

		$user2->fingerLengths = [1.1, 2.2, 3.3, 4.4, 5.1];
		$this->assertSame([1.1, 2.2, 3.3, 4.4, 5.1], $user2->fingerLengths);

		$user2->height = 2.42;
		$this->assertSame(2.42, $user2->height);

		$user2->hetero = true;
		$this->assertSame(true, $user2->hetero);

		// Ensure there is no property leakage from user2 to user1
		$this->assertSame(4242, $user1->id);
		$this->assertSame('Horse', $user1->name);
		$this->assertSame(42, $user1->age);
		$this->assertSame([0.1, 0.2, 0.3, 0.4, 0.1], $user1->fingerLengths);
		$this->assertSame(42.11, $user1->height);
		$this->assertSame(false, $user1->hetero);
	}

	/**
	 * Test unsetting properties.
	 */
	public function testUnset() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user                = new User(4242);
		$user->height        = 0.42;
		$user->hetero        = true;
		$user->fingerLengths = [0.42];
		$user->age           = 42;
		$user->name          = "Spectacular herring";
		unset($user->height);
		unset($user->hetero);
		unset($user->fingerLengths);
		unset($user->age);
		unset($user->name);

		$this->assertSame(null, $user->height);
		$this->assertSame(null, $user->hetero);
		$this->assertSame(null, $user->fingerLengths);
		$this->assertSame(null, $user->age);
		$this->assertSame(null, $user->name);
	}

	/**
	 * Test using isset to determine if a property is null.
	 */
	public function testIsset() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user                = new User(4242);
		$user->height        = 0.42;
		$user->hetero        = true;
		$user->fingerLengths = [0.42];
		$user->age           = 42;
		$user->name          = "Spectacular herring";
		unset($user->height);
		unset($user->hetero);
		unset($user->fingerLengths);

		$this->assertFalse(isset($user->height));
		$this->assertFalse(isset($user->hetero));
		$this->assertFalse(isset($user->fingerLengths));
		$this->assertTrue(isset($user->age));
		$this->assertTrue(isset($user->name));
	}

	/**
	 * Test if writing to a read-only property will throw an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testReadOnlyWrite() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user     = new User(4242);
		$user->id = 11;
	}

	/**
	 * Test if reading a write-only property will throw an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testWriteOnlyRead() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user       = new User(4242);
		$user->name = $user->writeOnly;
	}

	/**
	 * Test that checking if an undefined property isset throws an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testIssetMissingProperty() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);
		isset($user->funky);
	}

	/**
	 * Test that checking if a write only property isset throws an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testIssetWriteOnlyProperty() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);
		isset($user->writeOnly);
	}

	/**
	 * Test that unsetting a read only property throws an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testUnsetMissingProperty() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);
		unset($user->id);
	}

	/**
	 * Test that toArray() returns what is expected.
	 */
	public function testToArray() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$this->assertEquals([
			'id'            => 4242,
			'writeOnly'     => null,
			'name'          => null,
			'age'           => null,
			'fingerLengths' => null,
			'height'        => null,
			'hetero'        => null
		], $user->toArray());

		$user->name          = 'Horse';
		$user->age           = 42;
		$user->fingerLengths = [0.1, 0.2, 0.3, 0.4, 0.1];
		$user->height        = 42.11;
		$user->hetero        = false;

		$this->assertEquals([
			'id'            => 4242,
			'writeOnly'     => null,
			'name'          => 'Horse',
			'age'           => 42,
			'fingerLengths' => [0.1, 0.2, 0.3, 0.4, 0.1],
			'height'        => 42.11,
			'hetero'        => false
		], $user->toArray());
	}

	/**
	 * Test that toArray() returns what is expected, even if the docblock has not been parsed yet.
	 */
	public function testToArrayBeforeDocblockParsing() {
		require_once __DIR__ . '/../../fixture/Thing.php';

		$thing = new Thing();

		$this->assertEquals([], $thing->toArray());
	}

	/**
	 * Test that booleans cast as integers are 0 (false) and 1 (true).
	 */
	public function testBooleanToInteger() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->age = false;
		$this->assertSame(0, $user->age);

		$user->age = true;
		$this->assertSame(1, $user->age);
	}

	/**
	 * Test that casting an array to integer throws an exception.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testArrayToInteger() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user      = new User(4242);
		$user->age = ['horse'];
	}

	/**
	 * Test casting floats to strings.
	 */
	public function testFloatToString() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->name = 12.12;
		$this->assertSame('12.12', $user->name);

		$user->name = 0.12;
		$this->assertSame('0.12', $user->name);

		$user->name = 0.000000000042;
		$this->assertSame('4.2E-11', $user->name);

		$user->name = 0.0000000000;
		$this->assertSame('0.0', $user->name);

		$user->name = 0.0;
		$this->assertSame('0.0', $user->name);

		$user->name = .0;
		$this->assertSame('0.0', $user->name);

		$user->name = 0.;
		$this->assertSame('0.0', $user->name);
	}

	/**
	 * Test object with __toString implementation casts to string.
	 */
	public function testObjectWithToStringToString() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->name = $user;
		$this->assertSame("$user", $user->name);
	}

	/**
	 * Test object with no __toString implementation does not cast to string.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testObjectWithoutToStringToString() {
		require_once __DIR__ . '/../../fixture/User.php';
		require_once __DIR__ . '/../../fixture/Thing.php';

		$user = new User(4242);

		$user->name = new Thing();
	}

	/**
	 * Test that strings cannot be cast as arrays.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testStringToArray() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->fingerLengths = 'hello';
	}

	/**
	 * Test that integers cast properly to booleans.
	 */
	public function testIntegerToBoolean() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->hetero = 0;
		$this->assertSame(false, $user->hetero);

		$user->hetero = -42;
		$this->assertSame(true, $user->hetero);

		$user->hetero = 1;
		$this->assertSame(true, $user->hetero);

		$user->hetero = 42;
		$this->assertSame(true, $user->hetero);
	}

	/**
	 * Test that strings cast properly to booleans.
	 */
	public function testStringToBoolean() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->hetero = '';
		$this->assertSame(false, $user->hetero);

		$user->hetero = 'yessir';
		$this->assertSame(true, $user->hetero);

		$user->hetero = 'nope';
		$this->assertSame(true, $user->hetero);

		$user->hetero = 'almost but not entirely unlike tea';
		$this->assertSame(true, $user->hetero);
	}

	/**
	 * Test that arrays cannot be cast as booleans.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testArrayToBoolean() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->hetero = ['hello'];
	}

	/**
	 * Test that integers cast properly to floats.
	 */
	public function testIntegerToFloat() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->height = 42;
		$this->assertSame(42.0, $user->height);

		$user->height = 0;
		$this->assertSame(0.0, $user->height);

		$user->height = -54354397;
		$this->assertSame(-54354397.0, $user->height);
	}

	/**
	 * Test that strings cast properly to floats.
	 */
	public function testStringToFloat() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->height = '42';
		$this->assertSame(42.0, $user->height);

		$user->height = '42.0';
		$this->assertSame(42.0, $user->height);

		$user->height = '-54354397';
		$this->assertSame(-54354397.0, $user->height);

		$user->height = '-543.54397';
		$this->assertSame(-543.54397, $user->height);
	}

	/**
	 * Test that arrays cannot be cast as floats.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testArrayToFloat() {
		require_once __DIR__ . '/../../fixture/User.php';

		$user = new User(4242);

		$user->height = ['42'];
	}

	/**
	 * Test object property values.
	 */
	public function testObjectAssignment() {
		require_once __DIR__ . '/../../fixture/MoreTypes.php';
		require_once __DIR__ . '/../../fixture/User.php';

		$spork = new MoreTypes();

		$spork->object = new \stdClass();
		$this->assertInstanceOf('stdClass', $spork->object);

		$spork->object = $user = new User(11);
		$this->assertInstanceOf(get_class($user), $spork->object);
	}

	/**
	 * Test array to object casting does not work.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testArrayToObject() {
		require_once __DIR__ . '/../../fixture/MoreTypes.php';
		require_once __DIR__ . '/../../fixture/User.php';

		$spork = new MoreTypes();

		$spork->object = [];
	}

	/**
	 * Test resource assignment.
	 */
	public function testResourceAssignment() {
		require_once __DIR__ . '/../../fixture/MoreTypes.php';

		$spork = new MoreTypes();

		$spork->resource = fopen(__FILE__, 'r');

		$this->assertInternalType('string', fread($spork->resource, 42));

		fclose($spork->resource);
	}

	/**
	 * Test string to resource casting.
	 *
	 * @expectedException \Yilar\Exception
	 */
	public function testStringToResource() {
		require_once __DIR__ . '/../../fixture/MoreTypes.php';

		$spork = new MoreTypes();

		$spork->resource = __FILE__;
	}
}
