# Cartesian Iterator

Fork https://github.com/PatchRanger/cartesian-iterator

Iterator, returning the cartesian product of associative array of iterators. See https://en.wikipedia.org/wiki/Cartesian_product .

```php
<?php

$cartesianIterator = new \Enjoys\CartesianIterator();
$cartesianIterator->attachIterator(new \ArrayIterator([1,2]), 'test');
$cartesianIterator->attachIterator(new \ArrayIterator(['foo', 'bar']));

$result = iterator_to_array($cartesianIterator, false);
print_r($result);
```
Result:
```
Array
(
    [0] => Array
        (
            [test] => 1
            [1] => foo
        )

    [1] => Array
        (
            [test] => 2
            [1] => foo
        )

    [2] => Array
        (
            [test] => 1
            [1] => bar
        )

    [3] => Array
        (
            [test] => 2
            [1] => bar
        )

)
```