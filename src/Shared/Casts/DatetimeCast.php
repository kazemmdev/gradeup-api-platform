<?php

namespace Shared\Casts;

use Morilog\Jalali\Jalalian;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class DatetimeCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        $output = [];

        if (is_array($value)) {
            return $value;
        }

        if (! $value) {
            return [];
        }

        // Create DateTime object with the application timezone
        if (is_string($value)) {
            $datetime = new \DateTime($value);
        } elseif ($value instanceof \DateTime) {
            $datetime = clone $value;
        } else {
            return [];
        }

        // Set the timezone to Asia/Tehran
        $datetime->setTimezone(new \DateTimeZone('Asia/Tehran'));

        $timestamp = $datetime->getTimestamp();
        $hours = round((time() - $timestamp) / 3600, 1);

        $output['raw'] = $datetime->format('Y/m/d H:i:s');
        $output['relative'] = Jalalian::forge($datetime)->ago().($hours < 0 ? ' دیگر' : '');
        $output['long_format'] = Jalalian::forge($datetime)?->format('Y/m/d H:i');
        $output['date'] = Jalalian::forge($datetime)?->format('%d %B %Y');

        return $output;
    }
}
