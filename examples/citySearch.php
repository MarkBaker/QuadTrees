<?php

list(, $longitude, $latitude, $width, $height) = $argv + [null, -2.5, 55, 9, 10];

include __DIR__ . '/../classes/Bootstrap.php';


//  Create a class for our data,
//      extending QuadTrees\Coordinate so that we can use it for data points in our QuadTree
class CityPoint extends QuadTrees\Coordinate
{
    public $country;
    public $cityName;

    public function __construct($cityName, $country, $longitude, $latitude)
    {
        parent::__construct($longitude, $latitude);
        $this->country = $country;
        $this->cityName = $cityName;
    }
}


function buildQuadTree($filename)
{
    //  Set the centrepoint of our QuadTree at 0.0 Longitude, 0.0 Latitude
    $centrePoint = new QuadTrees\Coordinate();
    //  Set the bounding box to the entire globe
    $quadTreeBoundingBox = new QuadTrees\BoundingBox($centrePoint);
    //  Create our QuadTree
    $quadTree = new QuadTrees\PointQuadTree($quadTreeBoundingBox);

    echo "Loading cities: ";
    $cityFile = new \SplFileObject($filename);
    $cityFile->setFlags(\SplFileObject::READ_CSV | \SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);

    //  Populate our new QuadTree with cities from around the world
    $cityCount = 0;
    foreach ($cityFile as $cityData) {
        if (!empty($cityData[0])) {
            if ($cityCount % 1000 == 0) {
                echo '.';
            }
            $city = new CityPoint(
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

echo 'Load Time: ', sprintf('%.4f', $callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f', (memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f', (memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL, PHP_EOL;


/* Search for cities within a bounding box */
$startTime = microtime(true);

//  Create a bounding box to search in, centred on the specified longitude and latitude
$searchCentrePoint = new QuadTrees\Coordinate($longitude, $latitude);
//  Create the bounding box with specified dimensions
$searchBoundingBox = new QuadTrees\BoundingBox($searchCentrePoint, $width, $height);

$top = sprintf('%+2f', $searchBoundingBox->topLatitude());
$bottom = sprintf('%+2f', $searchBoundingBox->bottomLatitude());
$right = sprintf('%+2f', $searchBoundingBox->rightLongitude());
$left = sprintf('%+2f', $searchBoundingBox->leftLongitude());

//  Display the results
echo <<<EOT
Cities in range
    Latitude: $top -> $bottom
    Longitude: $right -> $left

EOT;


//  Search the cities QuadTree for all entries that fall within the defined bounding box
/** @var CityPoint $result */
foreach ($citiesQuadTree->search($searchBoundingBox) as $result) {
    echo '    ', $result->cityName, ', ',
        $result->country, ' => Lat: ',
        sprintf('%+07.2f', $result->getLatitude()), ' Long: ',
        sprintf('%+07.2f', $result->getLongitude()), PHP_EOL;
}
echo PHP_EOL;

$endTime = microtime(true);
$callTime = $endTime - $startTime;


echo 'Search Time: ', sprintf('%.4f', $callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f', (memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f', (memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL;
