ArrayBox
=======================

[![Circle CI](https://circleci.com/gh/aiiro/ArrayBox.svg?&style=shield)](https://circleci.com/gh/aiiro/ArrayBox)

ArrayBox is a PHP helper library that makes easy to manipulate the array.

## Installing 
PHP 5.6+ and [Composer](https://getcomposer.org/) are required.

Require this package with composer using the following command.
```bash
composer require aiiro/array-box
```

After Composer requiring, you need to require Composer's autoload.php.
```php
<?php 
  
require 'vendor/autoload.php';
```

## Functions

### \ArrayBox\ArrayBox
* sort2Dimensional - Sort two-dimensional array using the given $first, $second parameter.
* duplicatesInMultiDimensional - Find duplications in multi dimensional array.
* between - Retrieve the values within the given range.
* except - Retrieve the values except for the given value.

## Usage

#### Example
```php
<?php
  
$data = [
            ['volume' => 67, 'edition' => 2],
            ['volume' => 86, 'edition' => 1],
            ['volume' => 85, 'edition' => 6],
            ['volume' => 98, 'edition' => 1],
            ['volume' => 86, 'edition' => 3],
            ['volume' => 86, 'edition' => 2],
            ['volume' => 67, 'edition' => 7],
        ];
  
$array_box = new \ArrayBox\ArrayBox($data);
$sorted = $array_box->sort2Dimensional('volume', SORT_DESC, 'edition', SORT_ASC);
  
// Result
[
    ['volume' => 98, 'edition' => 1],
    ['volume' => 86, 'edition' => 1],
    ['volume' => 86, 'edition' => 2],
    ['volume' => 86, 'edition' => 3],
    ['volume' => 85, 'edition' => 6],
    ['volume' => 67, 'edition' => 2],
    ['volume' => 67, 'edition' => 7],
];

```

## License
ArrayBox is released under MIT License. See [MIT License](LICENSE)
 for the detail.
