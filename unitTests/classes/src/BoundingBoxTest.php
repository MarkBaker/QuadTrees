<?php

namespace QuadTrees;

use PHPUnit\Framework\TestCase;

class BoundingBoxTest extends TestCase
{
    public function testInstantiateWithCoordinate()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate());
        //    Must return an object...
        $this->assertTrue(is_object($boundingBoxObject));
        //    ... of the correct type
        $this->assertTrue(is_a($boundingBoxObject, __NAMESPACE__ . '\BoundingBox'));

        $defaulWidth = $boundingBoxObject->getWidth();
        $this->assertEquals(360.0, $defaulWidth);

        $defaultHeight = $boundingBoxObject->getHeight();
        $this->assertEquals(180.0, $defaultHeight);
    }

    public function testInstantiateWithAllArguments()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(15.0, -20.0), 10, 15);

        $longitude = $boundingBoxObject->getCentrePoint()->getLongitude();
        $this->assertEquals(15.0, $longitude);

        $latitude = $boundingBoxObject->getCentrePoint()->getLatitude();
        $this->assertEquals(-20.0, $latitude);

        $width = $boundingBoxObject->getWidth();
        $this->assertEquals(10.0, $width);

        $height = $boundingBoxObject->getHeight();
        $this->assertEquals(15.0, $height);
    }

    public function testLeftLongitude()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertEquals(121.5, $boundingBoxObject->leftLongitude());
    }

    public function testRightLongitude()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertEquals(128.5, $boundingBoxObject->rightLongitude());
    }

    public function testTopLatitude()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertEquals(-40.5, $boundingBoxObject->topLatitude());
    }

    public function testBottomLatitude()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertEquals(-49.5, $boundingBoxObject->bottomLatitude());
    }

    public function testIsLongitudeInBoundingBox()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertTrue($boundingBoxObject->isLongitudeInBoundingBox(123.45));
        $this->assertFalse($boundingBoxObject->isLongitudeInBoundingBox(120));
        $this->assertFalse($boundingBoxObject->isLongitudeInBoundingBox(130));
    }

    public function testIsLatitudeInBoundingBox()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertTrue($boundingBoxObject->isLatitudeInBoundingBox(-43));
        $this->assertFalse($boundingBoxObject->isLatitudeInBoundingBox(43));
        $this->assertFalse($boundingBoxObject->isLatitudeInBoundingBox(89));
    }

    public function testIsPointWithinBoundingBox()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertTrue($boundingBoxObject->isPointWithinBoundingBox(new Coordinate(123.45, -43)));
        $this->assertFalse($boundingBoxObject->isPointWithinBoundingBox(new Coordinate(123.45, -50)));
        $this->assertFalse($boundingBoxObject->isPointWithinBoundingBox(new Coordinate(123.45, -25)));
        $this->assertFalse($boundingBoxObject->isPointWithinBoundingBox(new Coordinate(130.0, -46)));
        $this->assertFalse($boundingBoxObject->isPointWithinBoundingBox(new Coordinate(120.0, -46)));
    }

    public function testIntersectsWith()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 10, 10);

        $this->assertTrue($boundingBoxObject->intersectsWith(new BoundingBox(new Coordinate(125.0, -45.0), 5, 5)));
        $this->assertTrue($boundingBoxObject->intersectsWith(new BoundingBox(new Coordinate(125.0, -35.0), 5, 15)));
        $this->assertTrue($boundingBoxObject->intersectsWith(new BoundingBox(new Coordinate(125.0, -45.0), 5, 30)));
        $this->assertFalse($boundingBoxObject->intersectsWith(new BoundingBox(new Coordinate(125.0, -55.0), 50, 5)));
        $this->assertFalse($boundingBoxObject->intersectsWith(new BoundingBox(new Coordinate(110.0, -45.0), 5, 50)));
    }

    public function testIsEncompassedBy()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertTrue($boundingBoxObject->isEncompassedBy(new BoundingBox(new Coordinate(125.0, -45.0), 8, 10)));
        $this->assertTrue($boundingBoxObject->isEncompassedBy(new BoundingBox(new Coordinate(125.0, -35.0), 7, 30)));
        $this->assertFalse($boundingBoxObject->isEncompassedBy(new BoundingBox(new Coordinate(125.0, -45.0), 4, 4)));
    }

    public function testEncompasses()
    {
        $boundingBoxObject = new BoundingBox(new Coordinate(125.0, -45.0), 7, 9);

        $this->assertTrue($boundingBoxObject->encompasses(new BoundingBox(new Coordinate(125.0, -45.0), 4, 4)));
        $this->assertFalse($boundingBoxObject->encompasses(new BoundingBox(new Coordinate(125.0, -45.0), 8, 10)));
        $this->assertFalse($boundingBoxObject->encompasses(new BoundingBox(new Coordinate(125.0, -35.0), 7, 30)));
    }
}
