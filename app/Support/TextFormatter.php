<?php

namespace App\Support;

use App\Domain\DTOs\Assessment\AlternativeDTO;
use Illuminate\Support\Str;

class TextFormatter
{
    public static function format(string $text): string
    {
        $strip_tags = html_entity_decode(
            strip_tags($text),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        return Str::of($strip_tags)
            ->replace("\u{A0}", ' ')
            ->squish()
            ->trim()
            ->toString();
    }

    public static function formattedAlternative(array $request) {
        return  collect($request)
        ->map(fn($data) => AlternativeDTO::fromApi($data))
        ->all();
    }
}
