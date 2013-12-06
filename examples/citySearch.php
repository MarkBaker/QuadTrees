<?php

list(, $longitude, $latitude, $width, $height) = $argv + array(NULL, -2.5, 55, 9, 10);

include __DIR__ . '/../src/PointQuadTree.php';


//  Create a class for our data,
//      extending quadTreeXYPoint so that we can use it for data points in our QuadTree
class cityPoint extends \quadTreeXYPoint
{
    public $country;
    public $city;

    public function __construct($country, $city, $x, $y) {
        parent::__construct($x, $y);
        $this->country = $country;
        $this->city = $city;
    }
}


function buildQuadTree($filename) {
    //  Set the centrepoint of our QuadTree at 0.0 Longitude, 0.0 Latitude
    $centrePoint = new quadTreeXYPoint(0.0, 0.0);
    //  Set the bounding box to the entire globe
    $quadTreeBoundingBox = new quadTreeBoundingBox($centrePoint, 360, 180);
    //  Create our QuadTree
    $quadTree = new PointQuadTree($quadTreeBoundingBox);

    echo "Loading cities: ";
    $cityFile = new \SplFileObject($filename);
    $cityFile->setFlags(SplFileObject::READ_CSV | SplFileObject::DROP_NEW_LINE | SplFileObject::SKIP_EMPTY);

    //  Populate our new QuadTree with cities from around the world
    $cityCount = 0;
    foreach($cityFile as $cityData) {
        if (!empty($cityData[0])) {
            if ($cityCount % 1000 == 0) echo '.';
            $city = new cityPoint(
                $cityData[0],
                $cityData[1],
                $cityData[3],
                $cityData[2]
            );
            $quadTree->insert($city);
            ++$cityCount;
        }
    }
    echo PHP_EOL, "Added $cityCount cities to QuadTree", PHP_EOL;
    return $quadTree;
}

/* Populate the quadtree  */
$startTime = microtime(true);

$citiesQuadTree = buildQuadTree(__DIR__ . "/../data/citylist.csv");

$endTime = microtime(true);
$callTime = $endTime - $startTime;

echo 'Load Time: ', sprintf('%.4f',$callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f',(memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f',(memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL, PHP_EOL;


/* Search for cities within a bounding box */
$startTime = microtime(true);

//  Create a bounding box to search in, centred on the specified longitude and latitude
$searchCentrePoint = new quadTreeXYPoint($longitude, $latitude);
//  Create the bounding box with specified dimensions
$searchBoundingBox = new quadTreeBoundingBox($searchCentrePoint, $width, $height);
//  Search the cities QuadTree for all entries that fall within the defined bounding box
$searchResult = $citiesQuadTree->search($searchBoundingBox);

//  Sort the results
usort(
    $searchResult,
    //  Sort by city name
    function($a, $b) {
        return strnatcmp($a->city, $b->city);
    }
);

//  Display the results
echo 'Cities in range', PHP_EOL, 
    "    Latitude: ", sprintf('%+2f',$searchBoundingBox->getCentrePoint()->getY() - $searchBoundingBox->getHeight() / 2),
    ' -> ', sprintf('%+2f',$searchBoundingBox->getCentrePoint()->getY() + $searchBoundingBox->getHeight() / 2), PHP_EOL,
    "    Longitude: ", sprintf('%+2f',$searchBoundingBox->getCentrePoint()->getX() - $searchBoundingBox->getWidth() / 2),
    ' -> ', sprintf('%+2f',$searchBoundingBox->getCentrePoint()->getX() + $searchBoundingBox->getWidth() / 2), PHP_EOL, PHP_EOL;

if (empty($searchResult)) {
    echo 'No matches found', PHP_EOL;
} else {
    foreach($searchResult as $result) {
        echo '    ', $result->city, ', ', 
            $result->country, ' => Lat: ', 
            sprintf('%+07.2f', $result->getY()), ' Long: ', 
            sprintf('%+07.2f', $result->getX()), PHP_EOL;
    }
}
echo PHP_EOL;

$endTime = microtime(true);
$callTime = $endTime - $startTime;

echo 'Search Time: ', sprintf('%.4f',$callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f',(memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f',(memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL;
