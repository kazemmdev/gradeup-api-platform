<?php

namespace Shared\Services\Embed\Models;

use Illuminate\Support\Arr;
use Module\Media\ValueObjects\MediaEmbed;
use Shared\Services\Embed\Concerns\EmbedInterface;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InApp implements EmbedInterface
{
    public function make(string $url): ?string
    {
        $app_url = config('app.url');
        $parsed_app_url = parse_url($app_url);
        $parsed_given_url = parse_url($this->ensureHttps($url));
        $app_url_host = Arr::get($parsed_app_url, 'host');
        $given_url_host = Arr::get($parsed_given_url, 'host');

        if (str_contains($app_url_host, $given_url_host)) {
            $url_path = Arr::get($parsed_given_url, 'path');
            $pattern = '#/media/(\d+)/[^/]+\.[a-zA-Z0-9]+#';
            preg_match($pattern, $url_path, $matches);
            $media = Media::find($matches[1]);

            return MediaEmbed::from($media)->render();
        }

        return null;
    }

    private function ensureHttps($url): string
    {
        if (str_starts_with($url, 'https://')) {
            return $url;
        }

        if (str_starts_with($url, 'http://')) {
            return 'https://'.substr($url, 7);
        }

        return 'https://'.ltrim($url, '/');
    }
}
