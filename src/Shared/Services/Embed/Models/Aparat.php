<?php

namespace Shared\Services\Embed\Models;

use Shared\Services\Embed\Concerns\EmbedInterface;

class Aparat implements EmbedInterface
{
    private array $regs = [
        '/(?:m\.)?aparat\.com\/v\/([\w-]+)/',
        '/(?:m\.)?aparat\.com\/v\/([\w\W-]+)/',
    ];

    public function make(string $url): ?string
    {
        foreach ($this->regs as $reg) {
            preg_match($reg, $url, $matches, PREG_OFFSET_CAPTURE, 0);
            if ($matches) {
                return '<style>.h_iframe-aparat_embed_frame{position:relative;}.h_iframe-aparat_embed_frame .ratio{display:block;width:100%;height:auto;}.h_iframe-aparat_embed_frame iframe{position:absolute;top:0;left:0;width:100%;height:100%;}</style>'.
                    '<div class="h_iframe-aparat_embed_frame"><span style="display: block;padding-top: 57%"></span>'.
                    '<iframe src="https://www.aparat.com/video/video/embed/videohash/'.$matches[1][0].'/vt/frame" allowFullScreen="true" '.
                    'webkitallowfullscreen="true" mozallowfullscreen="true"></iframe></div>';
            }
        }

        return null;
    }
}
