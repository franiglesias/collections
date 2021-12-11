<?php

namespace Fi\Collections\Utilities\Outline;

class Outline
{
    /**
     * @var array
     */
    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function extract($path)
    {
        if (empty($this->array)) {
            return null;
        }
        $segments = explode('.', $path);

        return $this->extractArray($this->array, (array) $segments);
    }

    private function extractArray($array, $segments)
    {
        $segment = array_shift($segments);

        if (count($segments) && isset($array[$segment])) {
            return $this->extractArray($array[$segment], $segments);
        }
        return $array[$segment];
    }
}
