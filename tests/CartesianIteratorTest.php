<?php

declare(strict_types=1);


namespace Tests\Enjoys\CartesianIterator;

use Enjoys\CartesianIterator\CartesianIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CartesianIteratorTest extends TestCase
{

    #[DataProvider('data')]
    public function testIterator($expect, $input)
    {


        $cartesianIterator = new CartesianIterator();
        foreach ($input as $item) {
            $cartesianIterator->attachIterator(new \ArrayIterator($item));
        }
        $this->assertSame($expect, iterator_to_array($cartesianIterator, false));
    }

    public static function data(): array
    {
        return [
            [
                [
                    [1,3],
                    [2,3],
                    [1,4],
                    [2,4],
                ],
                [
                    [1,2],
                    [3,4],
                ]
            ],
            [
                [
                    [1,4],
                    [2,4],
                    [3,4],
                    [1,5],
                    [2,5],
                    [3,5],
                    [1,6],
                    [2,6],
                    [3,6],
                ],
                [
                    [1,2,3],
                    [4,5,6],
                ]
            ],
        ];
    }
}
