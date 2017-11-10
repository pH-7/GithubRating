# Github Score

Get your Github Score in a click (useful to see how active you are on Github)


## Usage

```php
<?php
require 'src/Github/Score.php';

use Github\Score;

$sUsername = 'pH-7';
echo 'My Github Score: ' . Score::forUser($sUsername);
```
