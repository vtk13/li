<?php
namespace LocalInternet\Chess\Storage;

use LocalInternet\Chess\Desk;
use Redis;

abstract class RedisDeskStorage
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @var string
     */
    protected $key;

    public function __construct(string $host, int $port, string $key)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, $port);

        $this->key = $key;
    }

    public function saveDesk(Desk $desk)
    {
        $this->redis->set($this->key, $desk->serialize());
    }

    public function loadDesk(Desk $desk = null): Desk
    {
        if ($desk === null) {
            $desk = new Desk(0);
        }
        $desk->unserialize($this->redis->get($this->key));
        return $desk;
    }
}
