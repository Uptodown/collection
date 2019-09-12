<?php

namespace Uptodown\Collection;

use Closure;
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
        foreach ($array as $item) {
            $this->checkItemBelongToThis($item);
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

    public function remove($item) // : static
    {
        $this->checkItemBelongToThis($item);
        $this->collection = array_filter(
            $this->collection,
            function ($collectionItem) use ($item) {
                return ! $collectionItem->equals($item);
            }
        );
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

    public function map(callable $callback) // : array
    {
        return array_map(
            $callback,
            $this->collection
        );
    }
}
