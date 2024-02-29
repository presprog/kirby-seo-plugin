<?php declare(strict_types=1);

use Kirby\Cms\File;
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
            $options = PageMetaOptions::fromOptions();
            $ogImage = PageMeta::for($this->model())->siteImage();

            return $ogImage ? $ogImage->crop($options->ogImageWidth, $options->ogImageHeight)->url() : '';
        },

        'pageTitle' => function (): string {
            /** @var Section $this */
            return $this->model()->title()->toString();
        },

        'pageUrl' => function (): string {
            /** @var Section $this */
            return $this->model()->url();
        },

        'pageImage' => function (): string {
            /** @var Section $this */
            $options = PageMetaOptions::fromOptions();
            $pageImage = PageMeta::for($this->model())->pageImage();

            return $pageImage ? $pageImage->crop($options->ogImageWidth, $options->ogImageHeight)->url() : '';
        },

        'pageModelImage' => function (): null {
            /** @var Section $this */
            $options = PageMetaOptions::fromOptions();
            $pageModelImage = PageMeta::for($this->model())->pageModelImage();

            return $pageModelImage ? $pageModelImage->crop($options->ogImageWidth, $options->ogImageHeight)->url() : null;
        },

        'metaOptions' => function (): PageMetaOptions {
            return PageMetaOptions::fromOptions();
        },
    ],
];
