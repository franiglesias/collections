<?php

namespace Fi\Collections\Utilities\Outline;

class Outline
{
    private array $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }

    public function extract(string $path): ?string
    {
        if (empty($this->array)) {
            return null;
        }
        $segments = explode('.', $path);

        return $this->extractArray($this->array, (array) $segments);
    }

    private function extractArray(array $array, array $segments): string
    {
        $segment = array_shift($segments);

        if (isset($array[$segment]) && count($segments)) {
            return $this->extractArray($array[$segment], $segments);
        }
        return $array[$segment];
    }
}
