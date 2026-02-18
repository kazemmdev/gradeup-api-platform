<?php

namespace Shared\Services\Embed;

use Shared\Services\Embed\Concerns\EmbedInterface;
use Shared\Services\Embed\Models\Aparat;
use Shared\Services\Embed\Models\InApp;

class EmbedService
{
    /** @var EmbedInterface[] */
    protected array $embeds;

    public function __construct()
    {
        $this->embeds = [
            new InApp(),
            new Aparat(),
        ];
    }

    public function run(string $url): mixed
    {
        foreach ($this->embeds as $embed) {
            $result = $embed->make($url);
            if ($result != null) {
                return $result;
            }
        }

        return null;
    }
}
