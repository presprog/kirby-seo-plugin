# Metadata Plugin for Kirby 4

This plugin started as a copy of the metadata plugin included in the [getkirby.com](https://github.com/getkirby/getkirby.com) GitHub Repository.  We then stripped it down to what we needed and started using it in multiple of our own sites. Hence, kudos to Kirby team for their work and [thanks for sharing](https://github.com/getkirby/getkirby.com/issues/526). 

## Setup

Install with composer

```
composer require presprog/kirby-meta
```

## Configuration

Include the meta tab in your `site.yml`

```
# site.yml
tabs:
  meta:
    extends: tabs/meta
    fields: 
        metaTitle: false (turn off metatitle as a fallback title does not make sense. Every page has a title in Kirby 3)
```

This includes `metadescription` and `ogimage` fields on your dashboard. These serve as fallback if a page does not have any metadata itself.

Also include the `companyInfo.yml` section. From this information we compile a JSON-LD rich data snippet.

Add the meta tab to your page blueprint(s) as well. 

```
# default.yml|product.yml|home.yml|...
tabs:
  meta: tabs/meta
```

Whenever you define metadata like `metatitle`, `metadescription` or `ogimage` on your page, these will be used. When you do not define these, the fallbacks from `site.yml` will be used.

## Use in template

```php
<?php 
// some template
echo $page->meta()->title(); // Applies the above described logic
echo $page->meta()->head(); // Outputs meta description with above described logic and open graph tags
```

## That's all?

This plugin also includes
- sitemap.xml
- robots.txt
