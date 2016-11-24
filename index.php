<?php
use LocalInternet\Chess\Desk;
use LocalInternet\Chess\Figures\AbstractFigure;
use LocalInternet\Chess\Figures\King;
use LocalInternet\Chess\Figures\Pawn;
use LocalInternet\Chess\Storage\FileDeskStorage;

// simple integration test for FileDeskStorage
// you also can run unit tests with the vendor/bin/phpunit command

require_once 'vendor/autoload.php';

$storage = new FileDeskStorage('desk.json');

$desk = new Desk(8);
$desk->on(
    Desk::ON_FIGURE_ADDED,
    function(AbstractFigure $figure, int $x, int $y) {
        echo sprintf("Figure %s added to %s, %s\n", get_class($figure), $x, $y);
    }
);

$desk->addFigure(new Pawn(), 1, 1);
$desk->addFigure(new King(), 3, 4);
$desk->move($desk->getFigureAt(1, 1), 2, 2);
assert($desk->getFigureAt(2, 2) instanceof Pawn);
assert($desk->getFigureAt(3, 4) instanceof King);

$storage->saveDesk($desk);

$desk->clear();

assert($desk->getFigureAt(2, 2) === null);
assert($desk->getFigureAt(3, 4) === null);

$storage->loadDesk($desk);
assert($desk->getFigureAt(2, 2) instanceof Pawn);
assert($desk->getFigureAt(3, 4) instanceof King);
