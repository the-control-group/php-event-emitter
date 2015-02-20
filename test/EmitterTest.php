<?php

namespace TCG\Events;

class EmitterTest extends \PHPUnit_Framework_TestCase {

	public function testGlob2regexWithoutDeliminator(){
		$regex = Emitter::glob2regex('[a]:**:b', null);
		$this->assertEquals('/\[a\]\:\*\*\:b/', $regex);
	}

	public function testGlob2regexWithDeliminator(){
		$regex = Emitter::glob2regex('[a]:**:b', ':');
		$this->assertEquals('/\[a\]\:.*\:b/', $regex);
	}

	public function testOn(){
		$targetA = 0;
		$targetB = 0;
		$callableA = function($slug, $inc) use(&$targetA){ $targetA = $targetA + $inc; };
		$callableB = function($slug, $inc) use(&$targetB){ $targetB = $targetB + $inc; };
		$emitter = new Emitter();
		$emitter->on('aaa', $callableA);
		$emitter->on('bbb', $callableB);
		$emitter->emit('aaa', 1);
		$emitter->emit('bbb', -1);
		$this->assertEquals(1, $targetA);
		$this->assertEquals(-1, $targetB);
	}

	public function testOnWithMultiple(){
		$target = 0;
		$callableA = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$callableB = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$emitter = new Emitter();
		$emitter->on('aaa', $callableA);
		$emitter->on('aaa', $callableB);
		$emitter->emit('aaa', 1);
		$this->assertEquals(2, $target);
	}

	public function testOnWithDuplicates(){
		$targetA = 0;
		$callableA = function($slug, $inc) use(&$targetA){ $targetA = $targetA + $inc; };
		$emitter = new Emitter();
		$emitter->on('aaa', $callableA);
		$emitter->on('aaa', $callableA);
		$emitter->emit('aaa', 1);
		$this->assertEquals(1, $targetA);
	}

	public function testOffPattern(){
		$target = 0;
		$callableA = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$callableB = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$emitter = new Emitter();
		$emitter->on('aaa', $callableA);
		$emitter->on('aaa', $callableB);
		$emitter->off('aaa');
		$emitter->emit('aaa', 1);
		$this->assertEquals(0, $target);
	}

	public function testOffCallable(){
		$target = 0;
		$callableA = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$callableB = function($slug, $inc) use(&$target){ $target = $target + $inc; };
		$emitter = new Emitter();
		$emitter->on('aaa', $callableA);
		$emitter->on('aaa', $callableB);
		$emitter->off('aaa', $callableB);
		$emitter->emit('aaa', 1);
		$this->assertEquals(1, $target);
	}
}
