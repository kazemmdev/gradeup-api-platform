<?php

namespace Shared\ValueObjects;

use Carbon\Carbon;

class EndDate
{
    public Carbon $date;

    public function __construct(Carbon $date)
    {
        $this->date = $date->endOfDay();
    }

    public static function fromString(string $date): self
    {
        return new static(Carbon::parse($date));
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }
}
