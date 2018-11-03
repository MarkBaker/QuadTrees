<?php

namespace QuadTrees;

class PointQuadTree
{
    /**
     * Default for the maximum number of points that can be stored in any individual QuadTree node
     *    before it splits into child nodes
     *
     * @const   QUADTREE_NODE_CAPACITY
     **/
    const QUADTREE_NODE_CAPACITY = 10;

    /**
     * The Bounding box that this QuadTree node encompasses
     *
     * @var   BoundingBox
     **/
    private $boundingBox;

    /**
     * The maximum number of points that can be stored in this QuadTree node
     *
     * @var   integer
     **/
    private $maxPoints;

    /**
     * Array of the points that are actually stored in this QuadTree node
     *
     * @var   Coordinate[]
     **/
    private $points = [];

    /**
     * QuadTree node for the North-West quadrant of this QuadTree node
     *
     * @var   PointQuadTree
     **/
    private $northWest;

    /**
     * QuadTree node for the North-East quadrant of this QuadTree node
     *
     * @var   PointQuadTree
     **/
    private $northEast;

    /**
     * QuadTree node for the South-West quadrant of this QuadTree node
     *
     * @var   PointQuadTree
     **/
    private $southWest;

    /**
     * QuadTree node for the South-East quadrant of this QuadTree node
     *
     * @var   PointQuadTree
     **/
    private $southEast;


    /**
     * PointQuadTree constructor
     *
     * @param BoundingBox $boundingBox
     * @param int $maxPoints
     */
    public function __construct(BoundingBox $boundingBox, int $maxPoints = self::QUADTREE_NODE_CAPACITY)
    {
        $this->boundingBox = $boundingBox;
        $this->maxPoints = intval($maxPoints);
    }

    /**
     * Identifies whether this Bounding Box has been segmented into smaller Bounding Boxes
     *
     * @return bool
     */
    private function isSegmented()
    {
        return $this->northWest !== null;
    }

    /**
     * Insert a point Coordinate into this QuadTree node, segmenting it if it has reached bucket/node capacity
     *
     * @param Coordinate $point
     * @return bool
     */
    public function insert(Coordinate $point) : bool
    {
        if (!$this->boundingBox->isPointWithinBoundingBox($point)) {
            return false;
        }

        if (count($this->points) < $this->maxPoints) {
            // If we still have spaces in the bucket array for this QuadTree node,
            //    then the point simply goes here and we're finished
            $this->points[] = $point;
            return true;
        } elseif (!$this->isSegmented()) {
            // Otherwise, if it isn't already segmented, we split this node into NW/NE/SE/SW quadrants
            $this->segment();
        }

        // Insert the point into the appropriate segment/quadrant, and finish
        if ($this->northWest->insert($point)) {
            return true;
        }
        if ($this->northEast->insert($point)) {
            return true;
        }
        if ($this->southWest->insert($point)) {
            return true;
        }
        if ($this->southEast->insert($point)) {
            return true;
        }

        // If we couldn't insert the new point, then we have an exception situation
        throw new \OutOfBoundsException('Point is outside bounding box');
    }

    /**
     * Segment this QuadTree node
     */
    private function segment()
    {
        $segmentWidth = $this->boundingBox->getWidth() / 2;
        $segmentHeight = $this->boundingBox->getHeight() / 2;
        $centrePointX = $this->boundingBox->getCentrePoint()->getLongitude();
        $centrePointY = $this->boundingBox->getCentrePoint()->getLatitude();

        $this->northWest = new PointQuadTree(
            new BoundingBox(
                new Coordinate(
                    $centrePointX - $segmentWidth / 2,
                    $centrePointY + $segmentHeight / 2
                ),
                $segmentWidth,
                $segmentHeight
            ),
            $this->maxPoints
        );
        $this->northEast = new PointQuadTree(
            new BoundingBox(
                new Coordinate(
                    $centrePointX + $segmentWidth / 2,
                    $centrePointY + $segmentHeight / 2
                ),
                $segmentWidth,
                $segmentHeight
            ),
            $this->maxPoints
        );
        $this->southWest = new PointQuadTree(
            new BoundingBox(
                new Coordinate(
                    $centrePointX - $segmentWidth / 2,
                    $centrePointY - $segmentHeight / 2
                ),
                $segmentWidth,
                $segmentHeight
            ),
            $this->maxPoints
        );
        $this->southEast = new PointQuadTree(
            new BoundingBox(
                new Coordinate(
                    $centrePointX + $segmentWidth / 2,
                    $centrePointY - $segmentHeight / 2
                ),
                $segmentWidth,
                $segmentHeight
            ),
            $this->maxPoints
        );
    }

    /**
     * Get the number of points stored in this BoundingBox and its segments
     *
     * @return int
     */
    public function countPoints() : int
    {
        $count = count($this->points);

        // If we have child QuadTree nodes....
        if ($this->isSegmented()) {
            // ... search each child node in turn, adding to the existing result
            $count += $this->northWest->countPoints();
            $count += $this->northEast->countPoints();
            $count += $this->southWest->countPoints();
            $count += $this->southEast->countPoints();
        }

        return $count;
    }

    /**
     * Search this QuadTree Node (and all of its child nodes) for any Coordinates that fall within the
     *      specified Bounding Box
     *
     * @param BoundingBox $boundary
     * @return \Iterator|Coordinate[]
     */
    public function search(BoundingBox $boundary) : \Iterator
    {
        if ($this->boundingBox->isEncompassedBy($boundary) ||
            $this->boundingBox->intersectsWith($boundary)) {
            // Test each point that falls within the current QuadTree node
            foreach ($this->points as $point) {
                // Test each point stored in this QuadTree node in turn,
                //     passing back to the caller if it falls within the bounding box
                if ($boundary->isPointWithinBoundingBox($point)) {
                    yield $point;
                }
            }

            // If we have child QuadTree nodes....
            if ($this->isSegmented()) {
                // ... search each child node in turn, merging with any existing results
                yield from $this->northWest->search($boundary);
                yield from $this->northEast->search($boundary);
                yield from $this->southWest->search($boundary);
                yield from $this->southEast->search($boundary);
            }
        }
    }
}
