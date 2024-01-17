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
        return $this->page->site()->title()->value();
    }

    public function fullTitle(): string
    {
        $fullTitle = $this->title()->html();

        if ($this->options->appendSiteTitle) {
            $fullTitle = $this->title()->html() . $this->options->titleSeparator . site()->title()->html();
        }

        return $fullTitle;
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

        return $metaTitle;
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
            return new Field($this->page, 'fallbackDescription', '');
        }

        return $metaDescription;
    }

    public function openGraphImage(): ?File
    {
        if (method_exists($this->page, 'getOpenGraphImage')) {
            return $this->page->getOpenGraphImage();
        }

        if ($this->page->ogImage()->isNotEmpty()) {
            return $this->page->ogImage()->first()->toFile();
        }

        if (site()->ogImage()->isNotEmpty()) {
            return site()->ogImage()->first()->toFile();
        }

        return null;
    }

    public function ogType(): string
    {
        return 'website';
    }

    public function ogUrl(): string
    {
        return $this->page->url();
    }

    /**
     * @return array{"og:image": string, "og:image:width": int, "og:image:height": int, "og:image:alt"?: string}
     */
    public function ogImage(): array
    {
        $props = [];

        $image = $this->openGraphImage();

        if (!$image) {
            return [];
        }

        $cropped = $image->crop($this->options->ogImageWidth, $this->options->ogImageHeight);

        $props['og:image'] = $cropped->url();
        $props['og:image:width'] = $cropped->width();
        $props['og:image:height'] = $cropped->height();

        if ($image->alt()->isNotEmpty()) {
            $props['og:image:alt'] = $image->alt()->value();
        }

        return $props;
    }

    public function canonicalUrl(): string
    {
        return $this->page->url();
    }

    public function robots(): string
    {
        $robots = [
            $this->page->robotsIndex()->toBool() ? 'index' : 'noindex',
            $this->page->robotsFollow()->toBool() ? 'follow' : 'nofollow',
            $this->page->robotsArchive()->toBool() ? 'archive' : 'noarchive',
            $this->page->robotsImages()->toBool() ? 'imageindex' : 'noimageindex',
        ];

        return implode(', ', $robots);
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

    public static function for(Page $page, PageMetaOptions $options = null): self
    {
        if (!$options) {
            $options = PageMetaOptions::fromOptions();
        }

        return new PageMeta($page, $options);
    }
}
