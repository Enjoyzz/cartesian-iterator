<?php

declare(strict_types=1);

namespace Enjoys\CartesianIterator;

use Iterator;
use MultipleIterator;

final class CartesianIterator extends MultipleIterator
{
    /** @var Iterator[] */
    private $iterators = [];

    /** @var int */
    private $info = 0;

    public function __construct($flags = MultipleIterator::MIT_KEYS_ASSOC | MultipleIterator::MIT_NEED_ALL)
    {
        parent::__construct($flags  | MultipleIterator::MIT_NEED_ALL);
    }

    public function setFlags($flags): void
    {
        parent::setFlags($flags | MultipleIterator::MIT_NEED_ALL);
    }

    public function attachIterator(Iterator $iterator, $info = null): void
    {
        $this->iterators[] = $iterator;
        parent::attachIterator($iterator, $info ?? $this->info++);
    }

    public function detachIterator(Iterator $iterator): void
    {
        if (!$this->containsIterator($iterator)) {
            return;
        }

        parent::detachIterator($iterator);

        $key = array_search($iterator, $this->iterators, true);
        unset($this->iterators[$key]);

        $this->iterators = array_values($this->iterators);
    }

    public function next(int $index = 0): void
    {
        $iterator = $this->iterators[$index++];
        $iterator->next();
        if (!$iterator->valid() && $index < $this->countIterators()) {
            $iterator->rewind();
            $this->next($index);
        }
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this as $item) {
            $result[] = $item;
        }
        return $result;
    }

}