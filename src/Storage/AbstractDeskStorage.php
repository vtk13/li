<?php
namespace LocalInternet\Chess\Storage;

use LocalInternet\Chess\Desk;

abstract class AbstractDeskStorage
{
    abstract public function saveDesk(Desk $desk);

    abstract public function loadDesk(): Desk;
}
