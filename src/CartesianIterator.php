<?php

declare(strict_types=1);

namespace Enjoys\CartesianIterator;

class CartesianIterator extends \MultipleIterator
{
    /** @var \Iterator[] */
    protected $iterators = [];

    /** @var int */
    protected $key = 0;

    /** @var string[] */
    protected $infosHashMap = [];

    public function __construct()
    {
        parent::__construct(static::MIT_NEED_ALL | static::MIT_KEYS_ASSOC);
    }

    public function attachIterator(\Iterator $iterator, $info = null): void
    {
        $this->iterators[] = $iterator;
        if ($info === null) {
            $info = count($this->iterators) - 1;
        }
        if (isset($this->infosHashMap[$info])) {
            throw new \InvalidArgumentException("Iterator with the same key has been already added: {$info}");
        }
        $this->infosHashMap[$info] = spl_object_hash($iterator);
        parent::attachIterator($iterator, $info);
    }

    public function detachIterator(\Iterator $iterator): void
    {
        if (!$this->containsIterator($iterator)) {
            return;
        }
        parent::detachIterator($iterator);
        $iteratorHash = spl_object_hash($iterator);
        foreach ($this->iterators as $index => $iteratorAttached) {
            if ($iteratorHash === spl_object_hash($iteratorAttached)) {
                unset($this->iterators[$index]);
                break;
            }
        }
        $infos = array_flip($this->infosHashMap)[spl_object_hash($iterator)];
        unset($this->infosHashMap[$infos]);
    }

    #[\ReturnTypeWillChange]
    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->applyNext();
        $this->key += 1;
    }

    public function rewind(): void
    {
        parent::rewind();
        $this->key = 0;
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