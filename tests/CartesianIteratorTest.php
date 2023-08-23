<?php

declare(strict_types=1);


namespace Tests\Enjoys\CartesianIterator;

use Enjoys\CartesianIterator\CartesianIterator;
use PHPUnit\Framework\TestCase;

class CartesianIteratorTest extends TestCase
{


    public function testTwoIterators()
    {
        $cartesianIterator = new CartesianIterator();

        $expect = [
            [1, 'bar'],
            ['foo', 'bar'],
            [1, 42],
            ['foo', 42],
        ];
        $it1 = new \ArrayIterator([1, 'foo']);
        $it2 = new \ArrayIterator(['bar', 42]);

        $cartesianIterator->attachIterator($it1);
        $cartesianIterator->attachIterator($it2);

        $this->assertSame($expect, iterator_to_array($cartesianIterator, false));
        $this->assertSame($expect, $cartesianIterator->toArray());
    }

    public function testThreeIteratorsWithDiffSize()
    {
        $cartesianIterator = new CartesianIterator();

        $expect = [
            [1, 'bar', 42],
            ['foo', 'bar', 42],
            [1, 'baz', 42],
            ['foo', 'baz', 42],
            [1, 'xyz', 42],
            ['foo', 'xyz', 42],
        ];
        $it1 = new \ArrayIterator([1, 'foo']);
        $it2 = new \ArrayIterator(['bar', 'baz', 'xyz']);
        $it3 = new \ArrayIterator([42]);

        $cartesianIterator->attachIterator($it1);
        $cartesianIterator->attachIterator($it2);
        $cartesianIterator->attachIterator($it3);

        $this->assertSame($expect, iterator_to_array($cartesianIterator, false));
        $this->assertSame($expect, $cartesianIterator->toArray());
    }

    public function testCustomAssoc()
    {
        $cartesianIterator = new CartesianIterator();

        $expect = [
            ['it1' => 1, 'it2' => 1],
            ['it1' => 2, 'it2' => 1],
            ['it1' => 1, 'it2' => 2],
            ['it1' => 2, 'it2' => 2],
        ];
        $it1 = new \ArrayIterator([1, 2]);
        $it2 = new \ArrayIterator([1, 2]);

        $cartesianIterator->attachIterator($it1, 'it1');
        $cartesianIterator->attachIterator($it2, 'it2');

        $this->assertSame($expect, iterator_to_array($cartesianIterator, false));
        $this->assertSame($expect, $cartesianIterator->toArray());
    }

    public function testErrorKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Key duplication error');
        $cartesianIterator = new CartesianIterator();
        $cartesianIterator->attachIterator(new \ArrayIterator([]), 'key');
        $cartesianIterator->attachIterator(new \ArrayIterator([]), 'key');
    }

    public function testNumericKeysWithSetFlags()
    {
        $cartesianIterator = new CartesianIterator();
        $cartesianIterator->attachIterator(new \ArrayIterator([1, 2]), 'key');
        $cartesianIterator->attachIterator(new \ArrayIterator([3, 4]));
        $this->assertSame([
            ['key' => 1, 0 => 3],
            ['key' => 2, 0 => 3],
            ['key' => 1, 0 => 4],
            ['key' => 2, 0 => 4],
        ], $cartesianIterator->toArray());

        $cartesianIterator->setFlags(\MultipleIterator::MIT_KEYS_NUMERIC);
        $this->assertSame([
            [1, 3],
            [2, 3],
            [1, 4],
            [2, 4],
        ], $cartesianIterator->toArray());
    }

    public function testDetachIterators()
    {
        $it1 = new \ArrayIterator([1, 2]);
        $it2 = new \ArrayIterator([3, 4]);
        $it3 = new \ArrayIterator([5, 6]);
        $cartesianIterator = new CartesianIterator();
        $cartesianIterator->attachIterator($it1);
        $cartesianIterator->attachIterator($it2);
        $cartesianIterator->attachIterator($it3);
        $this->assertSame(3, $cartesianIterator->countIterators());
        $cartesianIterator->detachIterator($it2);
        $this->assertSame(2, $cartesianIterator->countIterators());
        $this->assertSame([
            [1, 2 => 5],
            [2, 2 => 5],
            [1, 2 => 6],
            [2, 2 => 6],
        ], $cartesianIterator->toArray());
        $cartesianIterator->setFlags(\MultipleIterator::MIT_KEYS_NUMERIC);
        $this->assertSame([
            [1, 5],
            [2, 5],
            [1, 6],
            [2, 6],
        ], $cartesianIterator->toArray());
    }

    public function testDetachNonAttachIterator()
    {
        $it1 = new \ArrayIterator([1, 2]);
        $it2 = new \ArrayIterator([3, 4]);
        $it3 = new \ArrayIterator([5, 6]);
        $cartesianIterator = new CartesianIterator();
        $cartesianIterator->attachIterator($it1);
        $cartesianIterator->attachIterator($it2);
        $this->assertSame(2, $cartesianIterator->countIterators());
        $cartesianIterator->detachIterator($it3);
        $this->assertSame(2, $cartesianIterator->countIterators());
    }

}
