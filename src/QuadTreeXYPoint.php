<?php

class QuadTreeXYPoint
{
    /**
     * X or Longitude coordinate of this point
     *
     * @var   float
     **/
    protected $x;

    /**
     * Y or Latitude coordinate of this point
     *
     * @var   float
     **/
    protected $y;


    /**
     * Create an x/y node point
     *
     * @param   float            $x         The X (or Longitude) coordinate of this point
     * @param   float            $y         The Y (or Latitude) coordinate of this point
     */
    public function __construct($x = 0.0, $y = 0.0) {
        if (!is_numeric($x) || !is_numeric($y))
            throw new \InvalidArgumentException("Point coordinates must be numeric values");
        $this->x = floatval($x);
        $this->y = floatval($y);
    }

    /**
     * Fetch the X coordinate (or Longitude) for this point
     *
     * @return  float                Value of the X (or Longitude) coordinate
     */
    public function getX() {
        return $this->x;
    }

    /**
     * Fetch the Y coordinate (or Latitude) for this point
     *
     * @return  float                Value of the Y (or Latitude) coordinate
     */
    public function getY() {
        return $this->y;
    }
}
