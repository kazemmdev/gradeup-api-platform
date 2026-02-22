<?php

namespace Module\Payment\Enums;

use Shared\Contract\EnumToArray;

enum PaymentStatus: string
{
    use EnumToArray;

    case PENDING = 'pending';
    case FAILED = 'failed';
    case PAID = 'paid';
    case VALID = 'valid';
    case INVALID = 'invalid';
}
