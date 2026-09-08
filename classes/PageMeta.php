<?php declare(strict_types=1);

namespace PresProg\KirbyMeta;

use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Content\Field;

readonly class PageMeta
{
    public function __construct(private Page $page, private PageMetaOptions $options)
    {
    }

    public function siteTitle(): string
    {
        return $this->apply('siteTitle', $this->page->site()->title()->value());
    }

    public function fullTitle(): string
    {
        $fullTitle = $this->title()->value();

        if ($this->options->appendSiteTitle) {
            $fullTitle .= $this->options->titleSeparator . site()->title()->value();
        }

        return $this->apply('fullTitle', $fullTitle);
    }

    /**
     * Returns the custom meta title OR
     * falls back to the regular page title.
     */
    public function title(): Field
    {
        $metaTitle = $this->page->metatitle();

        if ($metaTitle->isEmpty()) {
            $metaTitle = $this->page->title();
        }

        return $this->apply('title', $metaTitle);
    }

    /**
     * 1. Page has custom meta description defined OR
     * 2. Site has default meta description defined OR
     * 3. Page has `text` field that is not empty OR
     * 4. Fall back to empty string
     */
    public function description(): Field
    {
        $metaDescription = $this->page->metaDescription();

        if ($metaDescription->isEmpty() && site()->metaDescription()->isNotEmpty()) {
            $metaDescription = site()->metaDescription();
        }

        if ($metaDescription->isEmpty() && $this->page->text()->isNotEmpty()) {
            $metaDescription = $this->page->text();
        }

        if ($metaDescription->isEmpty()) {
            $metaDescription = new Field($this->page, 'fallbackDescription', '');
        }

        return $this->apply('description', $metaDescription);
    }

    public function siteImage(): ?File
    {
        $image = null;

        if ($this->page->site()->ogImage()->isNotEmpty()) {
            $image = $this->page->site()->ogImage()->first()->toFile();
        }

        return $this->apply('siteImage', $image);
    }

    public function pageImage(): ?File
    {
        $image = null;

        if ($this->page->ogImage()->isNotEmpty()) {
            $image = $this->page->ogImage()->first()->toFile();
        }

        return $this->apply('pageImage', $image);
    }

    public function pageModelImage(): ?File
    {
        $image = null;

        if (method_exists($this->page, 'getOpenGraphImage')) {
            $image = $this->page->getOpenGraphImage();
        }

        return $this->apply('pageModelImage', $image);
    }

    public function openGraphImage(): ?File
    {
        if ($image = $this->pageImage()) {
            return $this->apply('openGraphImage', $image);
        }

        $image = $this->pageModelImage();

        if (is_null($image)) {
            $image = $this->siteImage();
        }

        return $this->apply('openGraphImage', $image);
    }

    public function ogType(): string
    {
        return $this->apply('ogType', 'website');
    }

    public function ogUrl(): string
    {
        return $this->apply('ogUrl', $this->page->url());
    }

    /**
     * @return array{"url": string, "width": int, "height": int, "alt"?: string}|null
     */
    public function ogImage(): ?array
    {
        $props = [];

        $image = $this->openGraphImage();

        if (!$image) {
            return null;
        }

        $cropped = $image->crop($this->options->ogImageWidth, $this->options->ogImageHeight);

        $props['url'] = $cropped->url();
        $props['width'] = $cropped->width();
        $props['height'] = $cropped->height();
        $props['alt'] = $image->alt()->isNotEmpty() ? $image->alt()->value() : '';

        return $this->apply('ogImage', $props);
    }

    public function canonicalUrl(): string
    {
        return $this->apply('canonicalUrl', $this->page->url());
    }

    public function robots(): string
    {
        $robots = [
            $this->page->robotsIndex()->toBool() ? 'index' : 'noindex',
            $this->page->robotsFollow()->toBool() ? 'follow' : 'nofollow',
            $this->page->robotsArchive()->toBool() ? 'archive' : 'noarchive',
            $this->page->robotsImages()->toBool() ? 'imageindex' : 'noimageindex',
        ];

        return $this->apply('robots', implode(', ', $robots));
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

        return $this->apply('priority', (float)min(1, max(0, $priority)));
    }

    public static function for(Page $page, PageMetaOptions|null $options = null): self
    {
        if (!$options) {
            $options = PageMetaOptions::fromOptions();
        }

        return new PageMeta($page, $options);
    }

    private function apply(string $key, mixed $value): mixed
    {
        return kirby()->apply("presprog.seo.{$key}", [
            $key   => $value,
            'page' => $this->page,
            'meta' => $this,
        ], $key);
    }
}
