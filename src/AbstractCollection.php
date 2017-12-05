<?php

namespace Uptodown\Collection;

use Illuminate\Contracts\Support\Arrayable;
use Uptodown\EqualableInterface\Equalable;

abstract class AbstractCollection implements \IteratorAggregate, \Countable, \JsonSerializable, Arrayable
{
    protected $collection;

    public function __construct(array $array = [])
    {
        $this->checkArrayBelongToThis($array);
        $this->collection = $this->removeArrayKeys($array);
    }

    protected function checkArrayBelongToThis(array $array)
    {
        $this->checkArrayNotEmpty($array);
        foreach ($array as $item) {
            $this->checkItemBelongToThis($item);
        }
    }

    protected function checkArrayNotEmpty(array $array)
    {
        if (empty($array)) {
            throw new \InvalidArgumentException('Array can\'t be empty');
        }
    }

    protected function checkItemBelongToThis($item)
    {
        if (! is_object($item)
            || get_class($item) != static::CLASSNAME
            || ! class_implements(static::CLASSNAME, Equalable::class)
        ) {
            throw new \InvalidArgumentException('This item doesn\'t belong to the collection');
        }
    }

    protected function removeArrayKeys($array) // : array
    {
        return array_values($array);
    }

    public function add($item) // : static
    {
        $this->checkItemBelongToThis($item);
        $this->collection[] = $item;
        return $this;
    }

    public function has($item) // : bool
    {
        try {
            $this->checkItemBelongToThis($item);
            foreach ($this->collection as $collectionItem) {
                if ($collectionItem->equals($item)) {
                    return true;
                }
            }
            return false;
        } catch (\InvalidArgumentException $exception) {
            return false;
        }
    }

    public function end()
    {
        return end($this->collection);
    }

    public function addArray(array $array) // : static
    {
        while (! empty($array)) {
            $this->add(array_shift($array));
        }
        return $this;
    }

    public function merge(self $collection) // : static
    {
        $this->checkArrayBelongToThis($collection->toArray());
        return new static(array_merge($this->collection, $collection->toArray()));
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    public function count()
    {
        return count($this->collection);
    }

    public function jsonSerialize()
    {
        return json_encode($this->collection);
    }

    public function toArray()
    {
        return $this->collection;
    }
}
