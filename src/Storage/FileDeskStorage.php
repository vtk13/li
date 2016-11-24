<?php
namespace LocalInternet\Chess\Storage;

use LocalInternet\Chess\Desk;

class FileDeskStorage extends AbstractDeskStorage
{
    /**
     * @var string
     */
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function saveDesk(Desk $desk)
    {
        file_put_contents($this->filename, $desk->serialize());
    }

    public function loadDesk(Desk $desk = null): Desk
    {
        if ($desk === null) {
            $desk = new Desk(0);
        }
        $desk->unserialize(file_get_contents($this->filename));
        return $desk;
    }
}
