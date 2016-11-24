<?php
namespace LocalInternet\Chess;

/**
 * EventListenerTrait
 *
 * Very simple event listener without off() functionality
 */
trait EventListenerTrait
{
    protected $eventListeners = [];

    public function on(string $event, callable $callback)
    {
        $this->eventListeners[$event][] = $callback;
    }

    public function fire(string $event, array $arguments = [])
    {
        foreach ($this->eventListeners[$event] ?? [] as $callback) {
            call_user_func_array($callback, $arguments);
        }
    }
}
