<?php

namespace App\Traits;

use App\Support\HtmlCleaner;

trait SanitizesHtml
{
    protected function sanitizeHtml(?string $value): ?string
    {
        /** @var HtmlCleaner $cleaner */
        $cleaner = app(HtmlCleaner::class);

        return $cleaner->clean($value);
    }
}
