<?php

namespace TCG\Events;

class EventListener {

	protected $callables = array();




	// listen on a given pattern;
	// returns the total number of callables
	public function __construct(callable $callable = null){

		// don't add any callables
		if($callable === null)
			return count($this->callables);

		// add a callable
		return $this->add($callable);
	}




	// add a new callable if it is not already present;
	// returns the total number of callables
	public function add(callable $callable) {

		if(array_search($callable, $this->callables) !== false)
			return count($this->callables);

		array_push($this->callables, $callable);
		return count($this->callables);
	}




	// removes a callable if present;
	// returns the total number of callables
	public function remove(callable $callable) {

		if(($i = array_search($callable, $this->callables)) !== false)
			unset($this->callables[$i]);

		return count($this->callables);
	}




	// emit an event to all callables (the first parameter will always be the event slug);
	// returns the total number of callables
	public function emit() {
		foreach ($this->callables as $callable)
			call_user_func_array($callable, func_get_args());

		return count($this->callables);
	}
}
