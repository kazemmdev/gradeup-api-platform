<?php

namespace Shared\ValueObjects;

class Percent
{
    public function __construct(private readonly ?float $value) {}

    public static function from(?float $value): self
    {
        return new static($value);
    }

    public function format(string $defaultValue = ''): string
    {
        if ($this->value === null) {
            return $defaultValue;
        }

        return number_format($this->value * 100, 2).'%';
    }
}
