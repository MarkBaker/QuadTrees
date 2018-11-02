<?php

list(, $longitude, $latitude, $width, $height) = $argv + array(NULL, 0, 0, 40, 40);

include __DIR__ . '/../classes/Bootstrap.php';


//  Create a class for our data,
//      extending QuadTree\Coordinate so that we can use it for data points in our QuadTree
class LunarLandingPoint extends \QuadTrees\Coordinate
{
    public $name;
    public $launchDate;
    public $impactDate;

    public function __construct($name, $launchDate, $impactDate, $longitude, $latitude)
    {
        parent::__construct($longitude, $latitude);
        $this->name = $name;
        $this->launchDate = DateTime::createFromFormat('d M Y', $launchDate);
        $this->impactDate = DateTime::createFromFormat('d M Y', $impactDate);
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

    echo "Loading lunarLandings: ";
    $landingFile = new \SplFileObject($filename);
    $landingFile->setFlags(\SplFileObject::READ_CSV | \SplFileObject::DROP_NEW_LINE | \SplFileObject::SKIP_EMPTY);

    //  Populate our new QuadTree with lunarLandings from around the world
    $lunarLandingCount = 0;
    foreach ($landingFile as $lunarLandingData) {
        if (!empty($lunarLandingData[0])) {
            if ($lunarLandingCount % 10 == 0) {
                echo '.';
            }
            $lunarLanding = new LunarLandingPoint(
                trim($lunarLandingData[0]),
                trim($lunarLandingData[1]),
                trim($lunarLandingData[2]),
                trim($lunarLandingData[4]),
                trim($lunarLandingData[3])
            );
            $quadTree->insert($lunarLanding);
            ++$lunarLandingCount;
        }
    }
    echo PHP_EOL, "Added $lunarLandingCount lunar landing details to QuadTree", PHP_EOL;
    return $quadTree;
}

/* Populate the quadtree  */
$startTime = microtime(true);

$lunarLandingsQuadTree = buildQuadTree(__DIR__ . "/../data/lunarLandings.csv");

$endTime = microtime(true);
$callTime = $endTime - $startTime;

echo 'Load Time: ', sprintf('%.4f', $callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f', (memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f', (memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL, PHP_EOL;


/* Search for lunarLandings within a bounding box */
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
LunarLandings in range
    Latitude: $top -> $bottom
    Longitude: $left -> $right

EOT;

//  Search the lunarLandings QuadTree for all entries that fall within the defined bounding box
/** @var LunarLandingPoint $result */
foreach ($lunarLandingsQuadTree->search($searchBoundingBox) as $result) {
    echo '    ', $result->name, ",\tLaunched: ",
        $result->launchDate->format('d-M-Y'),
        ",\tLanded: ",
        $result->impactDate->format('d-M-Y'), "  Lat: ",
        sprintf('%+07.2f', $result->getLatitude()), " Long: ",
        sprintf('%+07.2f', $result->getLongitude()), PHP_EOL;
}
echo PHP_EOL;

$endTime = microtime(true);
$callTime = $endTime - $startTime;

echo 'Search Time: ', sprintf('%.4f', $callTime), ' s', PHP_EOL;
echo 'Current Memory: ', sprintf('%.2f', (memory_get_usage(false) / 1024 )), ' k', PHP_EOL;
echo 'Peak Memory: ', sprintf('%.2f', (memory_get_peak_usage(false) / 1024 )), ' k', PHP_EOL;
