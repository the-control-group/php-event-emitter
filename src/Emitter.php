<?php

namespace TCG\Event;

class Emitter {

	protected $listeners = array();
	protected $deliminator;



	// configure the deliminator used for event slugs
	public function __construct($deliminator = ':') {
		$this->deliminator = $deliminator;
	}




	public function on($pattern, callable $callable) {
		$regex = self::glob2regex($pattern, $this->deliminator);

		// listener already exists
		if(isset($this->listeners[$regex])) {
			$this->listeners[$regex]->add($callable);
			return;
		}

		// create a new listener
		$this->listeners[$regex] = new Listener($callable);
	}




	public function off($pattern, callable $callable = null) {
		$regex = self::glob2regex($pattern, $this->deliminator);

		// no such listener
		if(!isset($this->listeners[$regex])) return;

		// remove entire listener
		if($callable === null){
			unset($this->listeners[$regex]);
			return;
		}

		// remove callable and destroy listener if it's empty
		if($this->listeners[$regex]->remove($callable) === 0)
			unset($this->listeners[$regex]);
	}




	public function emit($slug) {
		$data = func_get_args();

		foreach ($this->listeners as $regex => $listener)
			if(preg_match($regex, $slug))
				call_user_func_array([$listener, 'emit'], $data);
	}




	// parse a glob using the configured deliminator and return a regex
	//
	// | pattern         | a:b:c | a:b:c:d | a:b:x |
	// |-------------------------------------------|
	// | foo:bar:baz     | Yes   | No      | No    |
	// | foo:bar:*       | Yes   | No      | Yes   |
	// | foo:*:*         | Yes   | No      | Yes   |
	// | foo:**:d        | Yes   | Yes     | Yes   |
	// | **              | Yes   | Yes     | Yes   |
	// | foo:bar         | No    | No      | No    |

	static function glob2regex($pattern, $deliminator = null) {
		$escaped_pattern = preg_quote($pattern);

		// we don't need to worry about a deliminator
		if($deliminator === null) return '/^' . $escaped_pattern . '$/';

		// build the regex string
		$escaped_deliminator = preg_quote($deliminator);
		return '/^' . implode(
			$escaped_deliminator,
			array_map(function($s) use($deliminator, $escaped_deliminator) {

				// single section wildcard
				if($s == '\*') return '[^'.$deliminator.']+';

				// multi-section wildcard
				if($s == '\*\*') return '.*';

				return $s;
			},
			explode($escaped_deliminator, $escaped_pattern)
		)) . '$/';
	}
}
