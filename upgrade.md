# Upgrade to the Kirby 6 release

This release requires Kirby 6 and PHP 8.3–8.6. Support for Kirby 4 and 5 has been
dropped; sites on those versions should stay on the plugin's 1.x release line.

The SEO tabs now define their settings directly under `columns.settings.fields`.
If you override fields in these tabs, update the old path
`columns.settings.sections.seoFields.fields` accordingly. Field names and stored
content stay the same. The Open Graph image field now uses `type: filepicker`.

The share preview is now a display field. If you use `seo/tabs/page`, its blueprint
already includes this change. If you placed the preview in a custom blueprint,
move it from `sections` to `fields`:

```yaml
fields:
  sharePreview:
    type: sharepreview
    label: share-preview-field-title
```

# Upgrade from 0.x.y to 1.0.0

## General

The plugin has been renamed to Kirby SEO Plugin. It will be installed to `site/plugins/seo` now rather than `seo/plugins/meta`. 

Due to that you must adjust your config and rename the parameters, e.g. `presprog.meta` becomes `presprog.seo`.

## Usage

The method `$page->meta()->head()` does not exist anymore. Use the `seo/head` snippet in your templates to render the markup. 

**In 0.x.x**
```php
// in <head> of a page template
<?= $page->meta()->head() ?>
```

**From 1.0.0**
```php
// in <head> of a page template
<?php snippet('seo/head') ?>
```

## Panel
The tab is now called SEO instead of Meta to communicate its purpose more clearly.
The blueprint has been renamed from `meta.yml` to `seo.yml`, too

## JSON-LD / Company info

Support for JSON-LD has been removed for now. It may be added back to 2.0 in a more useful way. 
The blueprint `fields/companyInfo` has been removed, too. You may copy the blueprint from the latest 0.x version and use it directly in your setup. 

## API
* Public methods `PageMeta::thumbnail()` and `PageMeta::hasOwnThumbnail()` have been removed
