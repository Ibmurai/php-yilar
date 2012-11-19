<?php

namespace Yilar;

/**
 * Analyzes scope... Don't worry about it.
 *
 * @author Jens Riisom Schultz <jers@fynskemedier.dk>
 */
class ScopeAnalyzer {
	use Traits\Singleton;

	/**
	 * Should be called from the magic functions implemented in \Yilar\Traits\Properties.
	 * Call with ($this, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))
	 *
	 * @param object $object         Any object, probably using the trait, \Yilar\Traits\Properties.
	 * @param array  $debugBacktrace debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), please.
	 *
	 * @return boolean True if the function is called one step below a private scope, for the class.
	 */
	public function isPrivate($object, array $debugBacktrace) {
		return isset($debugBacktrace[1]['class']) && $debugBacktrace[1]['class'] === get_class($object) ? true : false;
	}

	/**
	 * Should be called from the magic functions implemented in \Yilar\Traits\Properties.
	 * Call with ($this, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT))
	 *
	 * @param object $object Any object, probably using the trait, \Yilar\Traits\Properties.
	 * @param array  $debugBacktrace debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT), please.
	 *
	 * @return boolean True if the function is called one step below a SUPER private scope, for the class.
	 */
	public function isSuperPrivate($object, array $debugBacktrace) {
		return isset($debugBacktrace[1]['object']) && $debugBacktrace[1]['object'] === $object ? true : false;
	}
}
