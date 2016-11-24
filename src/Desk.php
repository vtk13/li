<?php
namespace LocalInternet\Chess;

use Exception;
use LocalInternet\Chess\Figures\AbstractFigure;
use Serializable;
use SplObjectStorage;

/**
 * Class Desk
 * @package LocalInternet\Chess
 */
class Desk implements Serializable
{
    use EventListenerTrait;

    const ON_FIGURE_ADDED = 'added';

    /**
     * @var SplObjectStorage|AbstractFigure[]
     */
    protected $figures = [];

    /**
     * @var AbstractFigure[][]
     */
    protected $desk = [];

    /**
     * @var int
     */
    protected $size;

    /**
     * Desk constructor.
     *
     * @param int $size
     */
    public function __construct(int $size)
    {
        $this->size = $size;
        $this->clear();
    }

    /**
     * @param AbstractFigure $figure
     * @param int $x
     * @param int $y
     * @throws Exception
     */
    public function addFigure(AbstractFigure $figure, int $x, int $y)
    {
        $this->validate($x, $y);

        if ($this->figures->contains($figure)) {
            throw new Exception('Figure already added on the desk');
        }

        if ($this->desk[$x][$y]) {
            throw new Exception(sprintf('Place %s, %s already occupied', $x, $y));
        }

        $this->figures[$figure] = [$x, $y];
        $this->desk[$x][$y] = $figure;

        $this->fire(self::ON_FIGURE_ADDED, [$figure, $x, $y]);
    }

    /**
     * @param int $x
     * @param int $y
     * @return AbstractFigure|null
     */
    public function getFigureAt(int $x, int $y)
    {
        $this->validate($x, $y);

        return $this->desk[$x][$y];
    }

    /**
     * @param AbstractFigure $figure
     * @param int $x
     * @param int $y
     * @throws Exception
     */
    public function move(AbstractFigure $figure, int $x, int $y)
    {
        $this->validate($x, $y);

        if (!$this->figures->contains($figure)) {
            throw new Exception('Figure does not belong to this desk');
        }

        if ($this->desk[$x][$y]) {
            // here may be some logic about capturing
            throw new Exception(sprintf('Place %s, %s already occupied', $x, $y));
        }

        list($oldX, $oldY) = $this->figures[$figure];

        if ($figure->canMove($oldX, $oldY, $x, $y)) {
            $this->desk[$oldX][$oldY] = null;
            $this->figures[$figure] = [$x, $y];
            $this->desk[$x][$y] = $figure;
        }
    }

    /**
     * @param AbstractFigure $figure
     */
    public function remove(AbstractFigure $figure)
    {
        if ($this->figures->contains($figure)) {
            list($x, $y) = $this->figures[$figure];
            $this->figures->detach($figure);
            $this->desk[$x][$y] = null;
        }
    }

    /**
     * Reset desk state
     */
    public function clear()
    {
        $this->figures = new SplObjectStorage();
        for ($x = 0 ; $x < $this->size ; $x++) {
            for ($y = 0 ; $y < $this->size ; $y++) {
                $this->desk[$x][$y] = null;
            }
        }
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $figures = [];
        foreach ($this->figures as $figure) {
            $coords = $this->figures[$figure];
            $figures[] = [$this->serializeFigure($figure), $coords];
        }
        return json_encode([$this->size, $figures]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list($size, $figures) = json_decode($serialized, true);
        $this->size = $size;
        $this->clear();
        foreach ($figures as $figureData) {
            list($figure, $coords) = $figureData;
            $figure = $this->unserializeFigure($figure);
            list($x, $y) = $coords;
            $this->addFigure($figure, $x, $y);
        }
    }

    /**
     * @param AbstractFigure $figure
     * @return string
     */
    protected function serializeFigure(AbstractFigure $figure)
    {
        // class names can be replaces with some constants for consistency
        return get_class($figure);
    }

    /**
     * @param string $data
     * @return AbstractFigure
     */
    protected function unserializeFigure(string $data)
    {
        $className = $data;
        return new $className();
    }

    /**
     * @param int $x
     * @param int $y
     * @throws Exception
     */
    protected function validate(int $x, int $y)
    {
        if ($x >=0 && $x < $this->size && $y >= 0 && $y < $this->size) {
            return;
        } else {
            throw new Exception(sprintf('Invalid %s, %s for desk with size %s', $x, $y, $this->size));
        }
    }
}
