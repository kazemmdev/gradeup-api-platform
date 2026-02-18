<?php

namespace Shared\ValueObjects;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class UntilDate
{
    public function __construct(public Carbon $date) {}

    public static function from(?string $date): self
    {
        return new static(Carbon::parse($date));
    }

    public function parse(): string
    {
        $datetime = strtotime($this->date);

        if (! $datetime) {
            return $this->date;
        }

        $hours = round((strtotime(now()) - $datetime) / 3600, 1);

        if ($hours < 24) {
            return Jalalian::forge($datetime)->ago().($hours < 0 ? ' دیگر' : '');
        }

        return Jalalian::forge($this->date)?->format('j F Y');
    }
}
