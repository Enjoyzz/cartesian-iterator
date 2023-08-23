<?php

declare(strict_types=1);

namespace Enjoys\CartesianIterator;

use Iterator;
use MultipleIterator;

class CartesianIterator extends MultipleIterator
{
    /** @var Iterator[] */
    protected array $iterators = [];

    public function __construct($flags = MultipleIterator::MIT_KEYS_ASSOC | MultipleIterator::MIT_NEED_ALL)
    {
        parent::__construct();
        $this->setFlags($flags);
    }

    public function setFlags(int $flags): void
    {
        parent::setFlags($flags | MultipleIterator::MIT_NEED_ALL);
    }

    public function attachIterator(Iterator $iterator, $info = null): void
    {
        $this->iterators[] = $iterator;
        parent::attachIterator($iterator, $info ?? $this->countIterators());
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