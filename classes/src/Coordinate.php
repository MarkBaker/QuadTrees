<?php

namespace QuadTrees;

class Coordinate
{
    /**
     * Longitude coordinate of this point
     *
     * @var   float
     **/
    private $longitude;

    /**
     * Latitude coordinate of this point
     *
     * @var   float
     **/
    private $latitude;

    /**
     * Coordinate constructor
     *
     * @param float $longitude
     * @param float $latitude
     */
    public function __construct(float $longitude = 0.0, float $latitude = 0.0)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    /**
     * Get the Longitude of this point
     *
     * @return float
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * Get the Latitude of this point
     *
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }
}
