<?php

namespace TCG\Events;

class Middleware extends \Slim\Middleware {

	// add a colon-separated event emitter to the app
	$this->app->events = new EventEmitter(':');

	// that's all, folks!
	public function call();

}
