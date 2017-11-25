<?php

namespace Fi\Collections;


use Test\Collections\MappedObject;

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

    protected function guardAgainstInvalidType($element): void
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
        $mapped = $this->instanceCollection($function);
        while ($object = next($this->elements)) {
            $mapped->append($function($object));
        }
        return $mapped;
    }

    protected function instanceCollection(Callable $function): Collection
    {
        $firstMapping = $function(reset($this->elements));
        $mapped = self::of(get_class($firstMapping));
        $mapped->append($firstMapping);
        return $mapped;
    }
}