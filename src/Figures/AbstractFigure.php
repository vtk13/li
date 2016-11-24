<?php
namespace LocalInternet\Chess\Figures;

abstract class AbstractFigure
{
    public function canMove(int $x, int $y, int $toX, int $toY)
    {
        // here we can check figure specific behaviour
        return true;
    }
}
