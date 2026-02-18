<?php

namespace Shared\Casts;

use Morilog\Jalali\Jalalian;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class UnixDatetimeCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): array
    {
        $output = [];

        if (empty($value) || is_string($value)) {
            return $value;
        }

        $datetime = new \DateTime("@$value");

        $hours = round((now()->getTimestamp() - $value) / 3600, 1);

        $output['raw'] = $datetime->format('Y/m/d H:i:s');
        $output['relative'] = Jalalian::forge($datetime)->ago().($hours < 0 ? ' دیگر' : '');
        $output['long_format'] = Jalalian::forge($value)?->format('j F Y H:i');

        return $output;
    }
}
