# SEO Plugin for Kirby CMS

This is our opinionated take on an SEO plugin for Kirby CMS. Edit meta descriptions and Open Graph data from within the panel – per page with a site-wide default – or programmatically.

<img src="/social-preview.png?raw=true" width="576" height="843" alt="A screenshot of the panel with three different link previews">

## Install

Install with Composer

```
composer require presprog/kirby-seo-plugin
```

## Setup

Include the SEO **site** tab in your `site.yml` and the SEO **page** tab in all your page blueprints

```
# site.yml
tabs:
  seo: seo/tabs/site

# e.g. default.yml
tabs:
  seo: seo/tabs/page
```

The site tab includes `metadescription` and `ogimage` fields on your dashboard. These serve as fallback if a page does not have any metadata itself.

The page tab includes fields for meta title, description and Open Graph image (share pic). You have three generic previews to see how your page being shared on different platforms may look. When you do not define these, the plugin falls back to the page title and the fallbacks for description and Open Graph image from `site.yml`.

## Use in template

```php
// in <head> of a page template
<?php snippet('seo/head') ?>
```

## Hierarchy

This plugin will look for an Open Graph image in the following order:

1. Page image (The image defined in the page blueprint)
2. Page model image (The image defined programmatically in a page model)
3. Site image (The site-wide fallback)

## Hooks

Modify metadata dynamically at runtime by defining hooks in your plugin or `config.php` using `presprog.seo.{key}`.

Hook callbacks receive the calculated value, the current `Page` instance, and the `PageMeta` instance (`$value, Page $page, PageMeta $meta`).

### Available Hooks

- `presprog.seo.siteTitle` (`string`)
- `presprog.seo.fullTitle` (`string`)
- `presprog.seo.title` (`Kirby\Content\Field`)
- `presprog.seo.description` (`Kirby\Content\Field`)
- `presprog.seo.siteImage` (`?Kirby\Cms\File`)
- `presprog.seo.pageImage` (`?Kirby\Cms\File`)
- `presprog.seo.pageModelImage` (`?Kirby\Cms\File`)
- `presprog.seo.openGraphImage` (`?Kirby\Cms\File`)
- `presprog.seo.ogType` (`string`)
- `presprog.seo.ogUrl` (`string`)
- `presprog.seo.ogImage` (`?array`)
- `presprog.seo.canonicalUrl` (`string`)
- `presprog.seo.robots` (`string`)
- `presprog.seo.priority` (`float`)

### Example

```php
use Kirby\Cms\Page;
use Kirby\Content\Field;
use PresProg\KirbyMeta\PageMeta;

'hooks' => [
    'presprog.seo.title' => function (Field $title, Page $page, PageMeta $meta): Field {
        if ($page->is('contact') && $hasError) {
            return new Field($page, 'metaTitle', 'Form error: ' . $title->value());
        }

        return $title;
    },
]
```

----

*The original version of this plugin started as a copy of the metadata plugin included in the [getkirby.com](https://github.com/getkirby/getkirby.com) website repository. We stripped it down to what we needed and started using it in multiple of our own sites. Thanks to the Kirby team for [sharing their work](https://github.com/getkirby/getkirby.com/issues/526).*

----

Made with ♥️ and ☕ by [Present Progressive](https://www.presentprogressive.de?utm_medium=referral&utm_source=github&utm_campaign=backlink&utm_content=readme)

<img src="/logo.svg?raw=true" width="200" height="43">
