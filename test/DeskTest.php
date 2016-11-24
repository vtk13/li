<?php
use LocalInternet\Chess\Desk;
use LocalInternet\Chess\Figures\Pawn;

class DeskTestClass extends PHPUnit_Framework_TestCase
{
    /**
     * @var Desk
     */
    protected $desk;

    public function setUp()
    {
        $this->desk = new Desk(8);
    }

    public function testAddFigureSuccess()
    {
        $pawn = new Pawn();
        $this->desk->addFigure($pawn, 0, 0);
        $this->assertSame($pawn, $this->desk->getFigureAt(0, 0));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid 100, 100 for desk with size 8
     */
    public function testAddFigureInvalidCoords()
    {
        $pawn = new Pawn();
        $this->desk->addFigure($pawn, 100, 100);
    }

    // here may be a lot of other tests
}
