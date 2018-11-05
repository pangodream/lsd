# lsd
Local Settings Director for PHP (read and write settings)

## Installation
````
composer require pangodream/php-lsd
````

## Usage example
Execute the sample twice to see how Option_B changes to a new value when put() method is invoked.
```php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use Lsd\Lsd;

Lsd::load(__DIR__.'/../.lsd');

echo Lsd::get('RouterAddress');
echo "\n";
Lsd::put('RouterAddress', '192.168.0.1');
Lsd::save();

echo Lsd::getLastReadTime();
echo "\n";
echo Lsd::getLastWriteTime();
echo "\n";
```

## .lsd file example
````
#This is an example of a .lsd file
#You should place the options you need in this file and rename it
#or create a new one

#Network Options
RouterAddress=10.0.0.1

OptionB=ValueB #Here is the explanation of OptionB value
OptionC=ValueC

OptionD=This option has a longer value

#And the next one has no value assigned
OptionE=
````