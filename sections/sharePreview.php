<?php declare(strict_types=1);

use Kirby\Cms\Section;
use PresProg\KirbyMeta\PageMeta;
use PresProg\KirbyMeta\PageMetaOptions;

return [
    'computed' => [

        'siteTitle' => function (): string {
            /** @var Section $this */
            return $this->model()->site()->title()->toString();
        },

        'siteDescription' => function (): string {
            /** @var Section $this */
            return $this->model()->site()->metaDescription()->toString();
        },

        'siteImage' => function (): string {
            return $this->model()->site()->ogImage()->toFile()?->url() ?? '';
        },

        'pageTitle' => function (): string {
            /** @var Section $this */
            return $this->model()->title()->toString();
        },

        'pageImage' => function (): string {
            $pageImage = PageMeta::for($this->model())->openGraphImage();
            return ($pageImage) ? $pageImage->url() : '';
        },

        'pageUrl' => function (): string {
            /** @var Section $this */
            return $this->model()->url();
        },

        'metaOptions' => function (): PageMetaOptions {
            return PageMetaOptions::fromOptions();
        },
    ],
];
