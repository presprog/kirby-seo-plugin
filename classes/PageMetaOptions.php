<?php declare(strict_types=1);

namespace PresProg\KirbyMeta;

readonly class PageMetaOptions
{
    public function __construct(
        public string $titleSeparator,
        public bool   $appendSiteTitle,
        public int    $ogImageWidth,
        public int    $ogImageHeight,
    )
    {
    }

    public static function fromOptions(): self
    {
        return new self(
            titleSeparator: option('presprog.meta.meta.titleSeparator', ' | '),
            appendSiteTitle: option('presprog.meta.meta.appendSiteTitle', true),
            ogImageWidth: option('presprog.meta.meta.ogImageWidth', 1200),
            ogImageHeight: option('presprog.meta.meta.ogImageHeight', 630),
        );
    }
}
