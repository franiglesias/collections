<?php

namespace Fi\Collections;

class Collection
{
    /**
     * @var array
     */
    private $elements = [];
    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function of(string $type): self
    {
        return new static($type);
    }

    public static function collect(array $elements): self
    {
        if (!count($elements)) {
            throw new \InvalidArgumentException('Can\'t collect an empty array');
        }

        $collection = static::of(get_class($elements[0]));

        array_map(function ($element) use ($collection) {
            $collection->append($element);
        }, $elements);

        return $collection;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function append($element): void
    {
        $this->guardAgainstInvalidType($element);
        $this->elements[] = $element;
    }

    protected function guardAgainstInvalidType($element): void
    {
        if (!$this->isSupportedType($element)) {
            throw new \UnexpectedValueException('Invalid Type');
        }
    }

    public function each(callable $function): Collection
    {
        if ($this->isEmpty()) {
            return $this;
        }

        array_map($function, $this->elements);

        return $this;
    }

    public function map(callable $function): Collection
    {
        if ($this->isEmpty()) {
            return clone $this;
        }

        $first = $function(reset($this->elements));
        $mapped = static::of(get_class($first));
        $mapped->append($first);

        while ($object = next($this->elements)) {
            $mapped->append($function($object));
        }

        return $mapped;
    }

    public function filter(callable $function): Collection
    {
        $filtered = static::of($this->getType());

        if ($this->isEmpty()) {
            return $filtered;
        }

        foreach ($this->elements as $element) {
            if ($function($element)) {
                $filtered->append($element);
            }
        }

        return $filtered;
    }

    public function getBy(callable $function)
    {
        if ($this->isEmpty()) {
            throw new \UnderflowException('Collection is empty');
        }
        foreach ($this->elements as $element) {
            if ($function($element)) {
                return $element;
            }
        }
        throw new \OutOfBoundsException('Element not found');
    }

    public function reduce(callable $function, $initial)
    {
        if ($this->isEmpty()) {
            return $initial;
        }

        foreach ($this->elements as $element) {
            $initial = $function($element, $initial);
        }

        return $initial;
    }

    public function toArray(callable $function = null): array
    {
        if ($this->isEmpty()) {
            return [];
        }
        if (!$function) {
            return $this->elements;
        }

        return array_map($function, $this->elements);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isEmpty(): bool
    {
        return !$this->count();
    }

    protected function isSupportedType($element): bool
    {
        return is_a($element, $this->getType());
    }
}
