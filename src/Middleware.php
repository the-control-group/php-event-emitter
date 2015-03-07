<?php

namespace TCG\Event;

class Middleware extends \Slim\Middleware {

	protected $emitter;

	public function __construct($deliminator = ':') {
		$this->emitter = new Emitter($deliminator);
	}

	public function call() {
		// add a colon-separated event emitter to the app
		$this->app->events = $this->emitter;

		// that's all, folks!
		$this->next->call();
	}

}
