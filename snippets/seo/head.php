<?php declare(strict_types=1);

/**
 * @var Site $site
 * @var Page $page
 */

use Kirby\Cms\Page;
use Kirby\Cms\Site;
use Kirby\Toolkit\Html;
use PresProg\KirbyMeta\PageMeta;

/** @var PageMeta $meta */
$meta = $page->meta();

// Meta tags
echo Html::tag('title', $meta->fullTitle()) . PHP_EOL;
echo Html::tag('meta', null, ['name' => 'description', 'content' => $meta->description()]) . PHP_EOL;

// Open Graph tags
echo Html::tag('meta', null, ['property' => 'og:site_name', 'content' => $meta->siteTitle()]) . PHP_EOL;
echo Html::tag('meta', null, ['property' => 'og:type', 'content' => $meta->ogType()]) . PHP_EOL;
echo Html::tag('meta', null, ['property' => 'og:url', 'content' => $meta->ogUrl()]) . PHP_EOL;
echo Html::tag('meta', null, ['property' => 'og:title', 'content' => $meta->fullTitle()]) . PHP_EOL;
echo Html::tag('meta', null, ['property' => 'og:description', 'content' => $meta->description()]) . PHP_EOL;

if ($image = $meta->ogImage()) {
    echo Html::tag('meta', null, ['property' => 'og:image', 'content' => $image['url']]) . PHP_EOL;
    echo Html::tag('meta', null, ['property' => 'og:image:width', 'content' => $image['width']]) . PHP_EOL;
    echo Html::tag('meta', null, ['property' => 'og:image:height', 'content' => $image['height']]) . PHP_EOL;
    if ($alt = $image['alt']) {
        echo Html::tag('meta', null, ['property' => 'og:image:alt', 'content' => $alt]) . PHP_EOL;
    }
}

// Robots
echo Html::tag('meta', null, ['property' => 'robots', 'content' => $meta->robots()]) . PHP_EOL;

// Links
echo Html::tag('link', null, ['rel' => 'canonical', 'href' => $meta->canonicalUrl()]) . PHP_EOL;
