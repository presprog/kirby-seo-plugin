# SEO Plugin for Kirby 4

This is our opionated take on a SEO plugin for Kirby 4. Edit meta descriptions and open graph data from within the panel – per page with a site-wide default – or programmatically. 

<img src="/social-preview.png?raw=true" width="576" height="843" alt="A screenshot of the panel with three different link previews">

## Install

Install with composer

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

The page tab includes fields for meta title, description and Open Graph Image (share pic). You have three generic previews to see how your page being shared on different platforms may look. When you do not define these, the plugin fallsback to the page title and the fallbacks for decsription and Open Graph Image from `site.yml`.

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

----

*The original version of this plugin started as a copy of the metadata plugin included in [getkirby.com](https://github.com/getkirby/getkirby.com) website Repository.  We stripped it down to what we needed and started using it in multiple of our own sites. Thanks to the Kirby team for [sharing their work](https://github.com/getkirby/getkirby.com/issues/526).*

----

Made with ♥️ and ☕ by [Present Progressive](https://www.presentprogressive.de)

<img src="/logo.svg?raw=true" width="200" height="43">
