[![Tests](https://github.com/Enjoyzz/cartesian-iterator/actions/workflows/test.yml/badge.svg)](https://github.com/Enjoyzz/cartesian-iterator/actions/workflows/test.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Enjoyzz/cartesian-iterator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/cartesian-iterator/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Enjoyzz/cartesian-iterator/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Enjoyzz/cartesian-iterator/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FEnjoyzz%2Fcartesian-iterator%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/Enjoyzz/cartesian-iterator/master)

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
