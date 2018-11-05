# lsd
Local Settings Director for PHP (read and write settings)

## Usage example
```php
<?php
/**
 * Created by Pangodream.
 * User: Development
 * Date: 05/11/2018
 * Time: 16:49
 */

require_once __DIR__.'/../vendor/autoload.php';

use Lsd\Lsd;

Lsd::load(__DIR__.'/../.lsd');

echo Lsd::get('Option_B');
echo "\n";
Lsd::put('Option_B', '192.168.0.1');
Lsd::save();

echo Lsd::getLastReadTime();
echo "\n";
echo Lsd::getLastWriteTime();
echo "\n";
```
