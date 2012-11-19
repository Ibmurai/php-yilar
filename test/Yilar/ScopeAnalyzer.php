<?php
namespace Yilar\Test;

require_once __DIR__ . '/BaseTest.php';

/**
 * ScopeAnalyzer.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class ScopeAnalyzer extends BaseTest {
	/**
	 * @covers Yilar\ScopeAnalyzer::getInstance
	 */
	public function testGetInstance() {
		$this->assertInstanceOf('Yilar\ScopeAnalyzer', \Yilar\ScopeAnalyzer::getInstance());
	}

	/**
	 * @covers Yilar\ScopeAnalyzer::isPrivate
	 */
	public function testIsPrivate() {

		$this->assertTrue($this->helperTestIsPrivate());
		$otherObjectWithSameClass = new ScopeAnalyzer();
		$this->assertTrue($otherObjectWithSameClass->helperTestIsPrivate());
		require_once __DIR__ . '/../fixture/User.php';
		$otherObjectWithDifferentClass = new User(42);
		$this->assertFalse($otherObjectWithDifferentClass->helperTestIsPrivate());
	}

	/**
	 * A helper to provide a calling context for the testIsPrivate test.
	 *
	 * @return boolean
	 */
	public function helperTestIsPrivate() {
		return \Yilar\ScopeAnalyzer::getInstance()->isPrivate($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
	}

	/**
	 * @covers Yilar\ScopeAnalyzer::isSuperPrivate
	 */
	public function testIsSuperPrivate() {
		$this->assertTrue($this->helperTestIsSuperPrivate());
		$otherObjectWithSameClass = new ScopeAnalyzer();
		$this->assertFalse($otherObjectWithSameClass->helperTestIsSuperPrivate());
		require_once __DIR__ . '/../fixture/User.php';
		$otherObjectWithDifferentClass = new User(42);
		$this->assertFalse($otherObjectWithDifferentClass->helperTestIsPrivate());
	}

	/**
	 * A helper to provide a calling context for the testIsSuperPrivate test.
	 *
	 * @return boolean
	 */
	public function helperTestIsSuperPrivate() {
		return \Yilar\ScopeAnalyzer::getInstance()->isSuperPrivate($this, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT));
	}
}
