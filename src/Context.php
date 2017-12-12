<?php

namespace Smuuf\Primi;

use \Smuuf\Primi\Structures\Value;
use \Smuuf\Primi\Structures\Func;

class Context extends \Smuuf\Primi\StrictObject implements IContext {

	// use WatchLifecycle;

	const EMPTY_CONTAINER = [
		'variables' => [],
		'functions' => [],
	];

	private $container = self::EMPTY_CONTAINER;

	public function reset() {
		$this->container = self::EMPTY_CONTAINER;
	}

	// Variables.

	public function setVariable(string $name, Value $value) {
		$this->container['variables'][$name] = $value;
	}

	/**
	 * Set multiple variables to the context using an array as parameter.
	 *
	 * @param array<string, Value> $pairs
	 */
	public function setVariables(array $pairs) {

		foreach ($pairs as $name => $value) {

			if (!$value instanceof Value) {
				$value = Value::buildAutomatic($value);
			}

			$this->setVariable($name, $value);

		}

	}

	public function getVariable(string $name): Value {

		if (!\array_key_exists($name, $this->container['variables'])) {
			throw new InternalUndefinedVariableException($name);
		}

		return $this->container['variables'][$name];

	}

	public function getVariables(): array {
		return $this->container['variables'];
	}

	// Functions.

	public function setFunction(string $name, Func $function) {
		$this->container['functions'][$name] = $function;
	}

	public function setFunctions(array $pairs) {

		foreach ($pairs as $name => $value) {
			$this->setFunction($name, $value);
		}

	}

	public function getFunction(string $name): Func {

		if (!\array_key_exists($name, $this->container['functions'])) {
			throw new InternalUndefinedFunctionException($name);
		}

		return $this->container['functions'][$name];
	}

	public function getFunctions(): array {
		return $this->container['functions'];
	}

	// Debugging.

	public function ___debug_zvals() {

		if (extension_loaded('xdebug_debug_zval')) {
			$tmp = $this;
			xdebug_debug_zval('tmp');
		}

	}

}
