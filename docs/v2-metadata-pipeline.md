# SEO Plugin v2 Metadata Pipeline

This document describes the planned v2 metadata API and resolution behavior.
It is a hand-off document for implementation, not user-facing documentation.

## Goals

- Keep `snippet('seo/head')` as the public template integration point.
- Replace the current `PageMeta`-centric internals with a well-defined metadata pipeline.
- Preserve existing Panel content storage and avoid content migrations.
- Preserve the current fallback behavior for all metadata already supported by v1.
- Allow developers to alter metadata before `seo/head` renders.
- Keep integration-specific behavior outside the SEO plugin. The SEO plugin exposes generic APIs; app-specific plugins and application code use those APIs.
- Support Kirby 5 only. Drop Kirby 4 support in v2 and update the Composer constraint accordingly.
- Update the PHP namespace from `PresProg\KirbyMeta` to `PresProg\KirbySeo`.

## Non-Goals

- Do not add query-template based metadata configuration in v2.
- Do not add Twitter card output in v2.
- Do not add hreflang/alternate output in v2.
- Do not implement low-level raw tag rendering in v2, but keep arbitrary keys possible so raw tag support can be added later.
- Do not expose providers as public extension API in v2.

## Public API

### Template API

Layouts continue to render metadata with:

```php
<?php snippet('seo/head') ?>
```

The snippet may change internally, but this include remains the stable public API.

### Global Helper

Expose a global helper:

```php
seo();
seo($page);
```

`seo()` requires a page context. Without an explicit page, it uses the current Kirby page.
If no page is available, it throws a clear exception.

`seo($page)` returns a mutable request/page SEO handle. It records request-scoped metadata changes for the targeted page. It does not persist content and does not render tags.

### Low-Level API

The mutable SEO handle supports:

```php
seo($page)->set('title', 'Example page');
seo($page)->get('title');
seo($page)->has('title');
seo($page)->remove('description');
seo($page)->document();
```

`get()` returns the resolved metadata value, not only explicitly set runtime values.

`document()` returns a read-only snapshot of resolved metadata. If the mutable handle is changed later, cached resolution for that page is invalidated and a later `document()` call returns a new snapshot.

### Fluent API

Fluent methods are setter-only convenience wrappers around `set()`. They return the mutable SEO handle for chaining.

```php
seo($page)
    ->title('Example page')
    ->description('Example description')
    ->ogTitle('Example page');
```

Reads stay explicit:

```php
seo($page)->get('title');
seo($page)->document()->get('fullTitle');
```

Do not support getter/setter overloads like `seo()->title()`.

Do not support closures/lazy values in `set()` or fluent setters for v2. Page-aware logic belongs in controllers, page models, hooks or local application plugins.

### Page Model API

Page models may define:

```php
use PresProg\KirbySeo\Seo;

public function seoMeta(Seo $seo): void
{
    $seo
        ->title($this->headline()->or($this->title())->value())
        ->description($this->intro()->excerpt(160)->value())
        ->image($this->heroImage()->toFile());
}
```

`seoMeta` is the required method name. Do not provide an option to rename it.
The `$seo` parameter is type-hinted intentionally; `PresProg\KirbySeo\Seo` is part of the v2 public API.

`seoMeta(Seo $seo)` receives the same mutable metadata API as `seo($page)`.
Values set in `seoMeta()` are programmatic defaults. Panel-entered values win over them when the Panel value is non-empty.

Runtime overrides win over `seoMeta()` values.

## Legacy Support

Support the current page-model method in v2:

```php
public function getOpenGraphImage(): ?File
```

Document it clearly as legacy/deprecated.

Implementation must isolate this behavior in one internal class, e.g. `LegacyPageModelProvider`, so v3 can remove it by removing that provider and its tests/docs.

New `seoMeta($seo)` values win over legacy values.

## Key Naming

Use PHP-style structured keys with dot notation for groups.

Built-in keys:

```text
title
fullTitle
siteTitle
titleSeparator
appendSiteTitle
description
canonicalUrl
image
og.siteName
og.type
og.url
og.title
og.description
og.image
og.imageAlt
og.imageWidth
og.imageHeight
robots.index
robots.follow
robots.archive
robots.images
robots
```

`set()` accepts arbitrary keys. Unknown keys are preserved in the resolved document and debug/array output, but the built-in `seo/head` snippet ignores unknown keys.

Do not support key aliases like `og:title`, `openGraph.title`, `openGraphTitle` or `ogTitle` in v2. Fluent methods such as `ogTitle()` are separate convenience methods, not metadata keys.

## Value Semantics

Three states matter:

```text
missing = not set, resolver may continue fallback behavior
''      = intentional empty value, render empty tag/value
null    = suppressed value, do not render the corresponding tag
```

`remove('key')` sets the resolved value to `null` for that key.

`seo($page)->get('key')` and `seo($page)->document()->get('key')` follow the same suppression behavior.

Empty strings render empty tags:

```php
seo($page)->description('');
```

renders:

```html
<meta name="description" content="">
```

Suppressed values do not render:

```php
seo($page)->remove('description');
```

renders no description tag.

## Source And Output Keys

Some keys are source keys. They are applied before derived metadata is computed:

```text
title
siteTitle
titleSeparator
appendSiteTitle
description
image
robots.index
robots.follow
robots.archive
robots.images
```

Some keys are output keys. They override emitted values exactly:

```text
fullTitle
canonicalUrl
og.siteName
og.type
og.url
og.title
og.description
og.image
og.imageAlt
og.imageWidth
og.imageHeight
robots
```

`seo()->set()` should write to the appropriate layer for the key.

Examples:

```php
// Change page title part and keep suffix behavior
seo($page)->title('Example page');

// Set final browser title exactly
seo($page)->fullTitle('Example page');

// Set Open Graph title independently
seo($page)->ogTitle('Example page');
```

## Resolution Pipeline

Providers/classes may be used internally, but they are not public extension API in v2.

Conceptual order:

```text
1. Built-in defaults and plugin options
2. Legacy page model defaults
3. Page model seoMeta($seo) defaults
4. Existing Panel content values, when non-empty
5. Runtime source overrides
6. Derived output keys
7. Runtime output overrides
8. Read-only document snapshot
9. seo/head rendering
```

Precedence:

```text
runtime overrides > non-empty Panel values > seoMeta() > legacy page model defaults > defaults/options
```

## Preserved Current Behavior

### Title

Current behavior:

```text
title = page metaTitle -> page title
fullTitle = title + titleSeparator + siteTitle when appendSiteTitle is true
og.title = fullTitle
```

v2 behavior:

```text
title = non-empty page metaTitle -> seoMeta title -> page title
siteTitle = site title
titleSeparator = option('presprog.seo.meta.titleSeparator', ' | ')
appendSiteTitle = option('presprog.seo.meta.appendSiteTitle', true)
fullTitle = composed title
og.title = fullTitle
```

If `title` is `null`, derived `fullTitle` becomes `siteTitle` when `appendSiteTitle` is true. If `appendSiteTitle` is false, derived `fullTitle` becomes `null`.

### Description

Current behavior:

```text
description = page metaDescription -> site metaDescription -> page text -> ''
og.description = description
```

v2 behavior:

```text
description =
  non-empty page metaDescription
  -> seoMeta description
  -> non-empty site metaDescription
  -> non-empty page text
  -> ''

og.description = description
```

Runtime `description` overrides affect the default `og.description`.
Runtime `og.description` overrides only affect Open Graph output.

### Canonical URL And Open Graph URL

Defaults:

```text
canonicalUrl = page.url
og.url = page.url
```

The keys are independent. Overriding one does not change the other:

```php
seo($page)->canonicalUrl('https://example.com/custom');
seo($page)->ogUrl('https://example.com/custom');
```

### Image

Current behavior:

```text
page ogImage -> page model getOpenGraphImage() -> site ogImage
```

v2 behavior:

```text
image =
  non-empty page ogImage
  -> seoMeta image
  -> legacy getOpenGraphImage()
  -> non-empty site ogImage
  -> null
```

Runtime image override wins over all:

```php
seo($page)->image($file);
```

`image` is a source key. It derives:

```text
og.image
og.imageWidth
og.imageHeight
og.imageAlt
```

Image width/height are generated from the configured Open Graph image size and only render when an image exists.

Manual width/height setters are not required as a user-facing concern, but the keys may exist in the resolved document because `head.php` needs to render them.

`og.imageAlt` must be individually overrideable.

Do not require special support for `remove('image')` suppressing all derived image output. Suppression should target output keys such as `og.image` or `og.imageAlt`.

### Robots

Existing Panel storage stays unchanged:

```text
robotsIndex
robotsFollow
robotsArchive
robotsImages
```

Public API keys:

```text
robots.index
robots.follow
robots.archive
robots.images
robots
```

Derived output:

```text
robots.index   true  -> index
robots.index   false -> noindex
robots.follow  true  -> follow
robots.follow  false -> nofollow
robots.archive true  -> archive
robots.archive false -> noarchive
robots.images  true  -> imageindex
robots.images  false -> noimageindex

robots = "index, follow, archive, imageindex"
```

Individual directives are source keys.
`robots` is an output key for exact final override.

## Rendering Behavior

`seo/head` renders only the same tag families as v1:

```html
<title>...</title>
<meta name="description" content="...">
<meta property="og:site_name" content="...">
<meta property="og:type" content="...">
<meta property="og:url" content="...">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">
<meta property="og:image:width" content="...">
<meta property="og:image:height" content="...">
<meta property="og:image:alt" content="...">
<meta property="robots" content="...">
<link rel="canonical" href="...">
```

Use `null` checks, not truthiness checks, so empty strings still render:

```php
if ($document->get('description') !== null) {
    // render meta description
}
```

Render `og.imageWidth` and `og.imageHeight` only when `og.image` is not `null`.

## Application Integration Example

Application code can set SEO state before `seo/head` renders.
This is useful for request-specific state that is only known during the current request:

```php
use Kirby\Cms\Page;

'page.render:before' => function (array $data, Page $page) {
    if (($data['hasFormErrors'] ?? false) === true) {
        seo($page)
            ->title('Please check your input')
            ->description('Please correct the highlighted fields.');
    }

    return $data;
}
```

This changes the page-title part and keeps the configured site-title suffix behavior.

## Open Implementation Choices

- Whether to keep `PageMeta` as an internal compatibility wrapper or remove it entirely.
- Whether to introduce a dedicated renderer class now. It is not required for v2 as long as `seo/head` remains clear and testable.
- Exact class names for the resolved document and internal providers.
- Exact test suite structure.
