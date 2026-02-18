<?php

namespace Shared\ValueObjects;

use Morilog\Jalali\Jalalian;

class UnixDateTime
{
    public mixed $date;

    public function __construct(string $value)
    {
        $this->date = new \DateTime("@$value");
    }

    public static function from(string $date): self
    {
        return new static($date);
    }

    public function ago(): string
    {
        return Jalalian::forge($this->date)->ago();
    }

    public function format(string $format): string
    {
        return Jalalian::forge($this->date)->format($format);
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }
}
