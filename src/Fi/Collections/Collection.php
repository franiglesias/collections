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

    public function __construct(string $type = null)
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
        if (!is_null($this->type) && !is_a($element, $this->type)) {
            throw new \OutOfBoundsException('Invalid Type');
        }
    }
}