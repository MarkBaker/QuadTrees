<?php
namespace QuadTrees;

use DeepCopy\f006\B;
use PHPUnit\Framework\TestCase;

class PointQUadTreeTest extends TestCase
{
    public function testInstantiate()
    {
        $quadTreeObject = new PointQuadTree(new BoundingBox(new Coordinate()));
        //    Must return an object...
        $this->assertTrue(is_object($quadTreeObject));
        //    ... of the correct type
        $this->assertTrue(is_a($quadTreeObject, __NAMESPACE__ . '\PointQUadTree'));
    }

    private $coordinateData = [
        [   5,   5 ],
        [  -5,   5 ],
        [  -5,  -5 ],
        [   5,  -5 ],
        [  10,  10 ],
        [ -10,  10 ],
        [ -10, -10 ],
        [  10, -10 ],
        [  15,  15 ],
        [ -15,  15 ],
        [ -15, -15 ],
        [  15, -15 ],
        [  20,  20 ],
        [ -20,  20 ],
        [ -20, -20 ],
        [  20, -20 ],
        [  25,  25 ],
        [ -25,  25 ],
        [ -25, -25 ],
        [  25, -25 ],
        [  30,  30 ],
        [ -30,  30 ],
        [ -30, -30 ],
        [  30, -30 ],
        [  35,  35 ],
        [ -35,  35 ],
        [ -35, -35 ],
        [  35, -35 ],
        [  40,  40 ],
        [ -40,  40 ],
        [ -40, -40 ],
        [  40, -40 ],
        [  45,  45 ],
        [ -45,  45 ],
        [ -45, -45 ],
        [  45, -45 ],
    ];

    public function testInsert()
    {
        $quadTreeObject = new PointQuadTree(new BoundingBox(new Coordinate()), 100);

        foreach ($this->coordinateData as $coordinate) {
            $quadTreeObject->insert(new Coordinate(...$coordinate));
        }

        $this->assertEquals(count($this->coordinateData), $quadTreeObject->countPoints());
    }

    public function testSearch()
    {
        $quadTreeObject = new PointQuadTree(new BoundingBox(new Coordinate()), 2);

        foreach ($this->coordinateData as $coordinate) {
            $quadTreeObject->insert(new Coordinate(...$coordinate));
        }

        $resultSet = [];
        $searchArea = new BoundingBox(new Coordinate(90.0, 45.0), 180.0, 90.0);

        foreach ($quadTreeObject->search($searchArea) as $result) {
            $resultSet[] = $result;
        }

        $expectedResults = array_filter(
            $this->coordinateData,
            function ($value) {
                return $value[0] >= 0.0 && $value[1] >= 0.0;
            }
        );

        $this->assertEquals(count($expectedResults), count($resultSet));
    }
}
