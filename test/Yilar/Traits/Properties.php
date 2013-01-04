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

		$user2->age = 4;
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

		$user                = new User(4242);

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
}
