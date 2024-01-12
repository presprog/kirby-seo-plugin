<?php declare(strict_types=1);

/**
 * @var Page $page
 * @var Site $site
 */

use Kirby\Cms\Html;
use Kirby\Cms\Page;
use Kirby\Cms\Site;

['meta' => $meta, 'opengraph' => $opengraph] = $page->meta()->head();

// Generate Meta Tags
foreach ($meta as $name => $content) {
    Html::tag('meta', null, [
        'name'    => $name,
        'content' => $content,
    ]) . PHP_EOL;
}

// Generate Open Graph Tags
foreach ($opengraph as $prop => $content) {
    Html::tag('meta', null, [
        'property' => $prop,
        'content'  => $content,
    ]) . PHP_EOL;
}
