<?php

namespace TCG\Events;

class EventListenerTest extends \PHPUnit_Framework_TestCase {

	public function testConstruct() {
		$target = 0;
		$listener = new EventListener();
		$listener->emit(1);
		$this->assertEquals(0, $target);
	}

	public function testConstructWithCallable() {
		$target = 0;
		$callable = function($inc) use(&$target){ $target = $target + $inc; };
		$listener = new EventListener($callable);
		$listener->emit(1);
		$this->assertEquals(1, $target);
	}

	public function testAdd() {
		$target = 0;
		$callable = function($inc) use(&$target){ $target = $target + $inc; };
		$listener = new EventListener();

		$count = $listener->add($callable);
		$this->assertEquals(1, $count);

		$listener->emit(1);
		$this->assertEquals(1, $target);
	}

	public function testAddDuplicate() {
		$target = 0;
		$callable = function($inc) use(&$target){ $target = $target + $inc; };
		$listener = new EventListener();

		$count = $listener->add($callable);
		$this->assertEquals(1, $count);

		$count = $listener->add($callable);
		$this->assertEquals(1, $count);

		$listener->emit(1);
		$this->assertEquals(1, $target);
	}

	public function testRemove() {
		$target = 0;
		$callable = function($inc) use(&$target){ $target = $target + $inc; };
		$listener = new EventListener();
		
		$count = $listener->add($callable);
		$this->assertEquals(1, $count);

		$count = $listener->remove($callable);
		$this->assertEquals(0, $count);

		$listener->emit(1);
		$this->assertEquals(0, $target);
	}

}
