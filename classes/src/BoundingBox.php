<?php

namespace QuadTrees;

class BoundingBox
{
    /**
     * Centre Point for this bounding box as a Latitude/Longitude coordinate
     *
     * @var   Coordinate
     **/
    private $centrePoint;

    /**
     * Width of this bounding box (in degrees)
     *
     * @var   float
     **/
    private $width;

    /**
     * Height of this bounding box (in degrees)
     *
     * @var   float
     **/
    private $height;


    /**
     * BoundingBox constructor
     *
     * @param Coordinate $centrePoint
     * @param float $width              Width of this bounding box (in degrees)
     * @param float $height             Height of this bounding box (in degrees)
     */
    public function __construct(Coordinate $centrePoint, float $width = 360.0, float $height = 180.0)
    {
        $this->centrePoint = $centrePoint;
        $this->width = floatval($width);
        $this->height = floatval($height);
    }

    /**
     * Get the Longitude of the Left side of this Bounding Box
     *
     * @return float
     */
    public function leftLongitude() : float
    {
        return $this->centrePoint->getLongitude() - $this->width / 2;
    }

    /**
     * Get the Latitude of the Top side of this Bounding Box
     *
     * @return float
     */
    public function topLatitude() : float
    {
        return $this->centrePoint->getLatitude() + $this->height / 2;
    }

    /**
     * Get the Longitude of the Right side of this Bounding Box
     *
     * @return float
     */
    public function rightLongitude() : float
    {
        return $this->centrePoint->getLongitude() + $this->width / 2;
    }

    /**
     * Get the Latitude of the Bottom side of this Bounding Box
     *
     * @return float
     */
    public function bottomLatitude() : float
    {
        return $this->centrePoint->getLatitude() - $this->height / 2;
    }

    /**
     * Identify whether a specified Longitude falls within the range of this Bounding Box
     *
     * @param float $longitude
     * @return bool
     */
    public function isLongitudeInBoundingBox(float $longitude) : bool
    {
        return ($longitude >= $this->leftLongitude()) && ($longitude <= $this->rightLongitude());
    }

    /**
     * Identify whether a specified Latitude falls within the range of this Bounding Box
     *
     * @param float $latitude
     * @return bool
     */
    public function isLatitudeInBoundingBox(float $latitude) : bool
    {
        return ($latitude <= $this->topLatitude()) && ($latitude >= $this->bottomLatitude());
    }

    /**
     * Identify whether a specified Coordinate point falls within the range of this Bounding Box
     *
     * @param Coordinate $point
     * @return bool
     */
    public function isPointWithinBoundingBox(Coordinate $point) : bool
    {
        return ($this->isLongitudeInBoundingBox($point->getLongitude()))
            && ($this->isLatitudeInBoundingBox($point->getLatitude()));
    }

    /**
     * Identify whether this Bounding Box intersects with another specified Bounding Box
     *
     * @param BoundingBox $boundary
     * @return bool
     */
    public function intersectsWith(BoundingBox $boundary) : bool
    {
        return (abs($this->centrePoint->getLongitude() - $boundary->centrePoint->getLongitude()) * 2
            <= ($this->width + $boundary->width))
        && (abs($this->centrePoint->getLatitude() - $boundary->centrePoint->getLatitude()) * 2
            <= ($this->height + $boundary->height));
    }

    /**
     * Identify whether this Bounding Box is encompassed by the specified Bounding Bax
     *
     * @param BoundingBox $boundary
     * @return bool
     */
    public function isEncompassedBy(BoundingBox $boundary) : bool
    {
        return
            (($boundary->leftLongitude() <= $this->leftLongitude()
                    && $boundary->rightLongitude() >= $this->rightLongitude()
                ) &&
                ($boundary->topLatitude() >= $this->topLatitude()
                    && $boundary->bottomLatitude() <= $this->bottomLatitude()
                ));
    }

    /**
     * Identify whether this Bounding Box encompasses the specified Bounding Bax
     *
     * @param BoundingBox $boundary
     * @return bool
     */
    public function encompasses(BoundingBox $boundary) : bool
    {
        return
            (($boundary->leftLongitude() >= $this->leftLongitude()
                    && $boundary->rightLongitude() <= $this->rightLongitude()
                ) &&
                ($boundary->topLatitude() <= $this->topLatitude()
                    && $boundary->bottomLatitude() >= $this->bottomLatitude()
                ));
    }

    /**
     * Gets the Coordinate of the centre point of this Bounding Box
     *
     * @return Coordinate
     */
    public function getCentrePoint() : Coordinate
    {
        return $this->centrePoint;
    }

    /**
     * Gets the width of this Bounding Box (in degrees)
     *
     * @return float
     */
    public function getWidth() : float
    {
        return $this->width;
    }

    /**
     * Gets the height of this Bounding Box (in degrees)
     *
     * @return float
     */
    public function getHeight() : float
    {
        return $this->height;
    }
}
