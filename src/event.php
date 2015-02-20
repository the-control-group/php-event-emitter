<?php

namespace TCG\Events;

class Event extends \Slim\Middleware {

	protected $listeners = array();

	public function call(){

        $app = $this->app;
        $event = $this;
		$app->event = $this;

		// call next middleware or app
		$this->next->call();
	}

	private function match($pattern, $name) {
		// TODO: return true/false if pattern matches name
		if($this->glob2regex($name)){

		}
	}

	public function on($pattern, $callable){
		array_push($this->listeners, array('pattern'=>$pattern, 'callable'=>$callable));
	}

	public function emit($name, $data) {
		foreach ($this->listeners as $listener)
			if($this->match($listener['pattern'], $name))
				call_user_func($listener['callable'], $name, $data);
	}

	private function glob2regex($string) {
	    return implode('\:',array_map(
	        function($s){
	            if($s == '\*') return '[^:]+';
	            if($s == '\*\*') return '.*';
	            return $s;
	        },
	        explode('\:', preg_quote($string))
	    ));
	}
}




// $app->event->on('account:order:*', function($name, $data){
// 	// fire a pixel
// 	echo "firing pixel!";
// });

// $app->event->emit('account:order:success', array(
// 	'amount' => 22.86,
// 	'foo' => 'bar'
// ));