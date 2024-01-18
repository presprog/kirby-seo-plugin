<?php declare(strict_types=1);

use PresProg\KirbyMeta\PageMetaOptions;

return [
    'props' => [],
    'computed' => [

        'metaOptions' => function() {
            return PageMetaOptions::fromOptions();
        },

        'siteTitle' => function(): string {
            /** @var \Kirby\Cms\Section $this */
            return $this->model()->site()->title()->toString();
        },

        'pageTitle' => function(): string {
            /** @var \Kirby\Cms\Section $this */
            return $this->model()->title()->toString();
        },

        'pageUrl' => function(): string
        {
            /** @var \Kirby\Cms\Section $this */
            return $this->model()->url();
        },

        'siteDescription' => function (): string {
            /** @var \Kirby\Cms\Section $this */
            return $this->model()->site()->metaDescription()->toString();
        },

        'siteImage' => function(): string
        {
            return $this->model()->ogImage()->toString();
        },
    ],
];
