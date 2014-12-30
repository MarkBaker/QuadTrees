QuadTrees
===========

A PHP implementation of the QuadTree data structure


## Requirements
 * PHP version 5.3.0 or higher


## Installation

We recommend installing this package with [Composer](https://getcomposer.org/ "Get Composer").

### Via composer

In your project root folder, execute

```
composer require markbaker/quadtrees:dev-master
```

You should now have the files `composer.json` and `composer.lock` as well as the directory `vendor` in your project directory.

You can then require the Composer autoloader from your code

```
require 'vendor/autoload.php';
```


Or, if you already have a composer.json file, then require this package in that file

```
"require": {
    "markbaker/quadtrees": "dev-master"
}
```

and update composer.

```
composer update
```

### From Phar

Although we strongly recommend using Composer, we also provide a [Phar archive](http://php.net/manual/en/book.phar.php "Read about Phar") builder that will create a Phar file containing all of the library code.

The phar builder script is in the repository root folder, and can be run using

```
php buildPhar.php
```

To use the archive, just require it from your script:

```
require 'QuadTrees.phar';
```

### Standard Autoloader

If you want to run the code without using composer's autoloader, and don't want to build the phar, then required the `bootstrap.php` file from the repository in your code, and this will enable the autoloader for the library.

```
require 'bootstrap.php';
```


## Want to contribute?
Fork this library!


## License
QuadTrees is licensed under [LGPL (GNU LESSER GENERAL PUBLIC LICENSE)](https://github.com/MarkBaker/QuadTrees/blob/master/LICENSE.md)


## Examples

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
