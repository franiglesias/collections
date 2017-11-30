<?php

namespace Fi\Collections;


use phpDocumentor\Reflection\Types\Callable_;
use Prophecy\Exception\InvalidArgumentException;
use stdClass;
use Test\Collections\CollectionTest;

class Collection
{
    /**
     * @var array
     */
    private $elements;
    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function of(string $type)
    {
        return new self($type);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function append($element)
    {
        $this->guardAgainstInvalidType($element);
        $this->elements[] = $element;
    }

    protected function guardAgainstInvalidType($element) : void
    {
        if (!$this->isSupportedType($element)) {
            throw new \UnexpectedValueException('Invalid Type');
        }
    }

    public function each(Callable $function)
    {
        if (!$this->count()) {
            return $this;
        }

        array_map($function, $this->elements);

        return $this;
    }

    public function map(Callable $function) : Collection
    {
        if (!$this->count()) {
            return clone $this;
        }

        $first = $function(reset($this->elements));
        $mapped = Collection::of(get_class($first));
        $mapped->append($first);

        while ($object = next($this->elements)) {
            $mapped->append($function($object));
        }

        return $mapped;
    }

    public function filter(Callable $function) : Collection
    {
        $filtered = Collection::of($this->type);

        if (!$this->count()) {
            return $filtered;
        }

        foreach ($this->elements as $element) {
            if ($function($element)) {
                $filtered->append($element);
            }
        }

        return $filtered;
    }

    public function getBy(Callable $function)
    {
        if (!$this->count()) {
            throw new \UnderflowException('Collection is empty');
        }
        foreach ($this->elements as $element) {
            if ($function($element)) {
                return $element;
            }
        }
        throw new \OutOfBoundsException('Element not found');
    }

    public function reduce(Callable $function, $initial)
    {
        if (!$this->count()) {
            return $initial;
        }
        foreach ($this->elements as $element) {
            $initial = $function($element, $initial);
        }
        return $initial;
    }

    public static function collect(array $elements)
    {
        if (!count($elements)) {
            throw new \InvalidArgumentException('Can\'t collect an empty array');
        }
        $collection = Collection::of(get_class($elements[0]));
        array_map(function ($element) use ($collection) {
            $collection->append($element);
        }, $elements);
        return $collection;
    }

    protected function isSupportedType($element) : bool
    {
        return is_a($element, $this->type);
    }

    public function toArray(Callable $function = null) : array
    {
        if (!$this->elements) {
            return [];
        }
        if (!$function) {
            return $this->elements;
        }
        return array_map($function, $this->elements);
    }

    public function getType()
    {
        return $this->type;
    }

    public function isEmpty() : bool
    {
        return !$this->elements;
    }
}