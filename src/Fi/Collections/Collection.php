<?php

namespace Fi\Collections;

use InvalidArgumentException;
use UnexpectedValueException;
use UnderflowException;
use OutOfBoundsException;

class Collection
{
    private array $elements = [];
    private string $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function ofType(string $type): self
    {
        return new self($type);
    }

    public static function collect(array $elements): self
    {
        if (!count($elements)) {
            throw new InvalidArgumentException('Can\'t collect an empty array');
        }

        $collection = self::ofType(self::getTypeOrClassOfElement($elements[0]));

        array_map(static function ($element) use ($collection) {
            $collection->append($element);
        }, $elements);

        return $collection;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function append(mixed $element): void
    {
        $this->guardAgainstInvalidType($element);
        $this->elements[] = $element;
    }

    protected function guardAgainstInvalidType(mixed $element): void
    {
        if (!$this->isSupportedType($element)) {
            throw new UnexpectedValueException('Invalid Type');
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
        $mapped = self::ofType(self::getTypeOrClassOfElement($first));
        $mapped->append($first);

        while ($object = next($this->elements)) {
            $mapped->append($function($object));
        }

        return $mapped;
    }

    public function filter(callable $function): Collection
    {
        $filtered = self::ofType($this->getType());

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

    public function getBy(callable $function): mixed
    {
        if ($this->isEmpty()) {
            throw new UnderflowException('Collection is empty');
        }
        foreach ($this->elements as $element) {
            if ($function($element)) {
                return $element;
            }
        }
        throw new OutOfBoundsException('Element not found');
    }

    public function reduce(callable $function, mixed $initial): mixed
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

    protected function isSupportedType(mixed $element): bool
    {
        $elementType = gettype($element);

        if ($elementType === 'object') {
            /** @var object $element */
            return is_a($element, $this->getType());
        }

        return $elementType === $this->getType();
    }

    protected static function getTypeOrClassOfElement(mixed $element): string
    {
        $elementType = gettype($element);

        if ($elementType === 'object') {
            /** @var object $element */
            return get_class($element);
        }

        return $elementType;
    }
}
