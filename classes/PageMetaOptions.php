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
            titleSeparator: option('presprog.seo.meta.titleSeparator', ' | '),
            appendSiteTitle: option('presprog.seo.meta.appendSiteTitle', true),
            ogImageWidth: option('presprog.seo.meta.ogImageWidth', 1200),
            ogImageHeight: option('presprog.seo.meta.ogImageHeight', 630),
        );
    }

    public function toArray(): array
    {
        return [
            'title_separator'   => $this->titleSeparator,
            'append_site_title' => $this->appendSiteTitle,
            'og_image_width'    => $this->ogImageWidth,
            'og_image_height'   => $this->ogImageHeight,
        ];
    }
}
