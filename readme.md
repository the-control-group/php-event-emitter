PHP Event Emitter
=================

A simple event emiter class with glob pattern matching.

Usage
-----

Using the base Emitter class:

```php
<?php

// create the emitter and set your glob deliminator
$emitter = new \TCG\Event\Emitter(':');

// some callable
$callable = function($event_slug, $foo=null, $bar=null){

	// do something here

}

// add a listener
$emitter->on('order:*', $callable);

// remove a listener
$emitter->off('order:*', $callable);

// remove all listeners for a given pattern
$emitter->off('order:*');

// emit an event
$emitter->emit('order:success', 123456, 22.86);


```

Using the provided [slim](http://www.slimframework.com) middleware:

```php
<?php

$app = new \Slim\Slim();

$app->use(new \TCG\Event\Middleware());

```


