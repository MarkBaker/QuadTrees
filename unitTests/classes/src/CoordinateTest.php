<?php

namespace QuadTrees;

use PHPUnit\Framework\TestCase;

class CoordinateTest extends TestCase
{
    public function testInstantiate()
    {
        $coordinateObject = new Coordinate();
        //    Must return an object...
        $this->assertTrue(is_object($coordinateObject));
        //    ... of the correct type
        $this->assertTrue(is_a($coordinateObject, __NAMESPACE__ . '\Coordinate'));

        $defaultCoordinateLongitude = $coordinateObject->getLongitude();
        $this->assertEquals(0.0, $defaultCoordinateLongitude);

        $defaultCoordinateLatitude = $coordinateObject->getLatitude();
        $this->assertEquals(0.0, $defaultCoordinateLatitude);
    }

    public function testInstantiateWithArguments()
    {
        $coordinateObject = new Coordinate(12.34, 56.78);

        $defaultCoordinateLongitude = $coordinateObject->getLongitude();
        $this->assertEquals(12.34, $defaultCoordinateLongitude);

        $defaultCoordinateLatitude = $coordinateObject->getLatitude();
        $this->assertEquals(56.78, $defaultCoordinateLatitude);
    }
}
