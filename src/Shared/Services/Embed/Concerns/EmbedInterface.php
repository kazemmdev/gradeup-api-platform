<?php

namespace Shared\Services\Embed\Concerns;

interface EmbedInterface
{
    public function make(string $url): mixed;
}
