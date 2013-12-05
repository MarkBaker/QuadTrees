<?php

include __DIR__ . '/QuadTreeXYPoint.php';


class QuadTreeBoundingBox
{
    /**
     * Centre Point for this bounding box as an X/Y coordinate
     *
     * @var   QuadTreeXYPoint
     **/
    protected $centrePoint;

    /**
     * Width of this bounding box
     *
     * @var   float
     **/
    protected $width;

    /**
     * Height of this bounding box
     *
     * @var   float
     **/
    protected $height;


    /**
     * Create a bounding box
     *
     * @param   QuadTreeXYPoint  $centrePoint   The X/Y point coordinate for the centre point of this bounding box
     * @param   float            $width         The width of this bounding box
     * @param   float            $height        The height of this bounding box
     */
    public function __construct(QuadTreeXYPoint $centrePoint, $width = 1.0, $height = 1.0) {
        if (!is_numeric($width) || !is_numeric($height))
            throw new \InvalidArgumentException("Bounding box width and height must be numeric values");
        $this->centrePoint = $centrePoint;
        $this->width = floatval($width);
        $this->height = floatval($height);
    }

    /**
     * Fetch the left-hand (start) X coordinate (or Longitude) for this bounding box
     *
     * @return  float                Value of the left-hand X (or Longitude) coordinate
     */
    protected function startX() {
        return $this->centrePoint->getX() - $this->width / 2;
    }

    /**
     * Fetch the bottom (start) Y coordinate (or Latitude) for this bounding box
     *
     * @return  float                Value of the bottom Y (or Latitude) coordinate
     */
    protected function startY() {
        return $this->centrePoint->getY() - $this->height / 2;
    }

    /**
     * Fetch the right-hand (end) X coordinate (or Longitude) for this bounding box
     *
     * @return  float                Value of the right-hand X (or Longitude) coordinate
     */
    protected function endX() {
        return $this->centrePoint->getX() + $this->width / 2;
    }

    /**
     * Fetch the top (end) Y coordinate (or Latitude) for this bounding box
     *
     * @return  float                Value of the top Y (or Latitude) coordinate
     */
    protected function endY() {
        return $this->centrePoint->getY() + $this->height / 2;
    }

    /**
     * Identifies whether a specified X (or Longitude) coordinate falls within the range of this bounding box?
     *
     * @param   float    $pointX       The specified X (or Longitude) coordinate to test
     * @return  boolean                Does the specified X coordinate fall within the range of this bounding box?
     */
    protected function isXinRange($pointX) {
        return ($pointX >= $this->startX()) && ($pointX <= $this->endX());
    }

    /**
     * Identifies whether a specified Y (or Latitude) coordinate falls within the range of this bounding box?
     *
     * @param   float    $pointY       The specified Y (or Latitude) coordinate to test
     * @return  boolean                Does the specified Y coordinate fall within the range of this bounding box?
     */
    protected function isYinRange($pointY) {
        return ($pointY >= $this->startY()) && ($pointY <= $this->endY());
    }

    /**
     * Identifies whether a specified X/Y point coordinate falls within the range of this bounding box?
     *
     * @param   QuadTreeXYPoint  $point   The specified X/Y point coordinate to test
     * @return  boolean                   Does the specified X/Y point coordinate fall within the range of this bounding box?
     */
    public function containsPoint(QuadTreeXYPoint $point) {
        return ($this->isXinRange($point->getX())) && ($this->isYinRange($point->getY()));
    }

    /**
     * Identifies whether a specified bounding box intersects with this bounding box?
     *
     * @param   QuadTreeBoundingBox  $boundary   The specified bounding box to test
     * @return  boolean                          Does the specified bounding box intersect with this bounding box?
     */
    public function intersects(QuadTreeBoundingBox $boundary) {
        return
            (($this->isXinRange($boundary->startX()) || $this->isXinRange($boundary->endX())) ||
            ($this->isYinRange($boundary->startY()) || $this->isYinRange($boundary->endY())));
    }

    /**
     * Identifies whether a specified bounding box completely encompasses this bounding box?
     *
     * @param   QuadTreeBoundingBox  $boundary   The specified bounding box to test
     * @return  boolean                          Does the specified bounding box completely encompasses this bounding box?
     */
    public function encompasses(QuadTreeBoundingBox $boundary) {
        return
            (($boundary->startX() <= $this->startX() && $boundary->endX() >= $this->endX()) &&
            ($boundary->startY() <= $this->startY() && $boundary->endY() >= $this->endY()));
    }

    /**
     * Fetch the Centre Point of this bounding box
     *
     * @return  QuadTreeXYPoint      Value of the Centre Point of this bounding box
     */
    public function getCentrePoint() {
        return $this->centrePoint;
    }

    /**
     * Fetch the Width of this bounding box
     *
     * @return  float                Value of the Width of this bounding box
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * Fetch the Height of this bounding box
     *
     * @return  float                Value of the Height of this bounding box
     */
    public function getHeight() {
        return $this->height;
    }
}
