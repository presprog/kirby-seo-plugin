<?php declare(strict_types=1);

namespace PresProg\KirbyMeta;

use Kirby\Content\Field;
use Kirby\Cms\File;
use Kirby\Toolkit\Html;

/**
 *
 */
class PageMeta
{
    protected $page;

    public function __construct($page)
    {
        $this->page = $page;
    }

    public function head(): array
    {
        $meta = [];
        $opengraph = [];
        $site = site();

        // Basic OpenGraph tags
        $opengraph['og:site_name'] = $site->title()->value();
        $opengraph['og:url'] = $this->page->url();
        $opengraph['og:type'] = 'website';

        $opengraph['og:title'] = $this->title() . ' | ' . $site->title();

        // Meta and OpenGraph description
        $description = $this->description();

        if ($description->isNotEmpty()) {
            $opengraph['og:description'] = $description->excerpt(200);
            $meta['description'] = $description->excerpt(160);
        }

        // Image
        $openGraphImage = $this->openGraphImage();
        if ($openGraphImage) {
            $croppedOgImage = $openGraphImage->crop(1200, 630);

            $opengraph['og:image'] = $croppedOgImage->url();
            $opengraph['og:image:width'] = $croppedOgImage->width();
            $opengraph['og:image:height'] = $croppedOgImage->height();

            if ($openGraphImage->alt()->isNotEmpty()) {
                $opengraph['og:image:alt'] = $openGraphImage->alt()->value();
            }
        }

        // Robots
        $meta['robots'] = $this->page->robots()->isNotEmpty() ? $this->page->robots()->value() : 'index,follow';

        return [
            'meta' => $meta,
            'opengraph' => $opengraph,
        ];
    }

    /**
     * 1. Page has custom meta title defined OR
     * 2. Fall back to page title
     */
    public function title(): Field
    {
        $metaTitle = $this->page->metatitle();
        return !$metaTitle->isEmpty() ? $metaTitle : $this->page->title();
    }

    /**
     * 1. Page has custom meta description defined OR
     * 2. Site has default meta description defined OR
     * 3. Page has `text` field that is not empty OR
     * 4. Fall back to empty string
     * @return string
     */
    public function description()
    {
        $metaDescription = $this->page->metaDescription();

        if ($metaDescription->isEmpty() && site()->metaDescription()->isNotEmpty()) {
            $metaDescription = site()->metaDescription();
        }

        if ($metaDescription->isEmpty() && $this->page->text()->isNotEmpty()) {
            $metaDescription = $this->page->text();
        }

        if ($metaDescription->isEmpty()) {
            return new Field($this->page, 'fallbackDescription', '');
        }

        return $metaDescription;
    }

    public function openGraphImage(): ?File
    {
        if (method_exists($this->page, 'getOpenGraphImage')) {
            return  $this->page->getOpenGraphImage();
        }

        if ($this->page->ogImage()->isNotEmpty()) {
            return $this->page->ogImage()->first()->toFile();
        }

        if (site()->ogImage()->isNotEmpty()) {
            return site()->ogImage()->first()->toFile();
        }

        return null;
    }

    public function getFile(string $key, bool $fallback = true): ?File
    {
        $key = strtolower($key);

        $field = $this->page->content()->get($key);

        if ($field->exists() && ($file = $field->toFile())) {
            return $file;
        }

        if ($fallback === true) {
            return site()->content()->get($key)->toFile();
        }

        return null;
    }

    public function priority(): float
    {
        $priority = null;

        if (empty($priority) === true) {
            $priority = 0.5;
        }

        return (float)min(1, max(0, $priority));
    }
}
