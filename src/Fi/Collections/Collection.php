<?php

namespace Fi\Collections;


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
        if (!is_a($element, $this->type)) {
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
}