<?php

namespace App\Support;

use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class HtmlCleaner
{
    protected HtmlSanitizer $sanitizer;

    public function __construct(?HtmlSanitizer $sanitizer = null)
    {
        $this->sanitizer = $sanitizer ?? $this->buildSanitizer();
    }

    public function clean(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $trimmed = Str::of($html)->trim();

        if ($trimmed->isEmpty()) {
            return null;
        }

        return $this->sanitizer->sanitize((string) $html);
    }

    protected function buildSanitizer(): HtmlSanitizer
    {
        $config = (new HtmlSanitizerConfig())
            ->allowSafeElements()
            ->allowRelativeLinks()
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowMediaSchemes(['http', 'https', 'data'])
            ->allowRelativeMedias();

        foreach ([
            'figure', 'figcaption', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'code',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td', 'blockquote',
        ] as $element) {
            $config = $config->allowElement($element, '*');
        }

        $config = $config
            ->allowElement('img', ['src', 'alt'])
            ->allowElement('a', '*')
            ->allowAttribute('target', ['a'])
            ->allowAttribute('rel', ['a'])
            ->forceAttribute('a', 'rel', 'noopener noreferrer')
            ->allowAttribute('colspan', ['td', 'th'])
            ->allowAttribute('rowspan', ['td', 'th'])
            ->blockElement('iframe')
            ->blockElement('script')
            ->blockElement('style');

        return new HtmlSanitizer($config);
    }
}
