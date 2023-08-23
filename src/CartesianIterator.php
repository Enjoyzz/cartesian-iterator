<?php

declare(strict_types=1);

namespace Enjoys\CartesianIterator;

class CartesianIterator extends \MultipleIterator
{
    /** @var \Iterator[] */
    protected $iterators = [];

    /** @var string[] */
    protected $infosHashMap = [];

    public function __construct($flags = \MultipleIterator::MIT_KEYS_ASSOC | \MultipleIterator::MIT_NEED_ALL)
    {
        parent::__construct();
        $this->setFlags($flags);
    }

    public function setFlags(int $flags): void
    {
        parent::setFlags($flags | \MultipleIterator::MIT_NEED_ALL);
    }

    public function attachIterator(\Iterator $iterator, $info = null): void
    {
        $this->iterators[] = $iterator;
        parent::attachIterator($iterator, $info ?? $this->countIterators());
    }

    public function detachIterator(\Iterator $iterator): void
    {
        if (!$this->containsIterator($iterator)) {
            return;
        }
        parent::detachIterator($iterator);
    }


    public function next(): void
    {
        $this->applyNext();
    }

    private function applyNext(int $index = 0): void
    {
        $iterator = $this->iterators[$index];
        $iterator->next();
        if (!$iterator->valid() && $index < $this->countIterators() - 1) {
            $iterator->rewind();
            $this->applyNext($index + 1);
        }
    }
}