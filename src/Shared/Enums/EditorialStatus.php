<?php

namespace Shared\Enums;

use Shared\Contract\EnumToArray;

enum EditorialStatus: string
{
    use EnumToArray;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case SCHEDULED = 'scheduled';
    case PUBLISHED = 'published';
    case PROCESSING = 'processing';
    case ARCHIVED = 'archived';
}
