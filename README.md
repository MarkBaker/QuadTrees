QuadTrees
===========

A PHP implementation of the Point QuadTree data structure

The /examples folder contains a couple of examples to demonstrate its use:

 - citySearch.php

    allows a search on a defined bounding box, and will display a list of cities that fall within that bounding box
    
    usage:

        php citySearch.php <latitude> <longitude> <width> <height>

        latitude   Latitude of the centre point of the bounding box
                     (as a floating-point value in degrees, positive values
                     for East, negative for West)
        longitude  Longitude of the centre point of the bounding box
                     (as a floating-point value in degrees, positive values
                     for North, negative for South)
        width      Width of the bounding box 
                     (as a floating-point value in degrees)
        height     Height of the bounding box
                     (as a floating-point value in degrees)


Data for the list of cities is from [geonames.org](http://www.geonames.org/ "Geonames"), licensed under a [Creative Commons Attribution 3.0 License](http://creativecommons.org/licenses/by/3.0/ "Creative Commons Attribution License 3.0"), and provided "as is" without warranty or any representation of accuracy, timeliness or completeness.

 - lunarLandingSearch.php

    allows a search on a defined bounding box, and will display a list of moon landings that fall within that bounding box
    
    usage:

        php lunarLandingSearch.php <latitude> <longitude> <width> <height>

        latitude   Latitude of the centre point of the bounding box
                     (as a floating-point value in degrees, positive values
                     for East, negative for West)
        longitude  Longitude of the centre point of the bounding box
                     (as a floating-point value in degrees, positive values
                     for North, negative for South)
        width      Width of the bounding box 
                     (as a floating-point value in degrees)
        height     Height of the bounding box
                     (as a floating-point value in degrees)
