<?php declare(strict_types=1);

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Language;
use Kirby\Data\Yaml;
use Kirby\Filesystem\F;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Kirby\Toolkit\Xml;
use PresProg\KirbyMeta\PageMeta;

Kirby::plugin('presprog/meta', [

    'blueprints' => [
        'seo/tabs/site'           => __DIR__ . '/blueprints/tabs/site.yml',
        'seo/tabs/page'           => __DIR__ . '/blueprints/tabs/page.yml',
        'seo/fields/robotsGroup'  => __DIR__ . '/blueprints/fields/robotsGroup.yml',
        'seo/fields/robotsToggle' => __DIR__ . '/blueprints/fields/robotsToggle.yml',
    ],

    'options' => [
        'meta.appendSiteTitle'     => true,
        'meta.titleSeparator'      => ' | ',
        'sitemap.templatesInclude' => [],
        'sitemap.pagesInclude'     => [],
        'sitemap.pagesExclude'     => [],
    ],

    'pageMethods' => [
        'meta' => function () {
            return PageMeta::for($this);
        },
    ],

    'snippets' => [
        'seo/head' => __DIR__ . '/snippets/seo/head.php',
    ],

    'sections' => [
        'sharePreview' => __DIR__ .'/sections/sharePreview.php',
    ],

    'routes' => [
        [
            'pattern' => 'robots.txt',
            'method'  => 'ALL',
            'action'  => function () {
                $robots = 'User-agent: *' . PHP_EOL;
                $robots .= 'Allow: /' . PHP_EOL;

                if (kirby()->multilang()) {
                    /** @var Language $language */
                    foreach (kirby()->languages() as $language) {
                        $robots .= Url::makeAbsolute('/sitemap.xml', $language->url()) . PHP_EOL;
                    }
                } else {
                    $robots .= 'Sitemap: ' . url('sitemap.xml');
                }

                return kirby()
                    ->response()
                    ->type('text')
                    ->body($robots);
            },
        ], [
            'pattern'  => 'sitemap.xml',
            'language' => '*',
            'action'   => function () {
                $templatesIncludeList = option('presprog.meta.sitemap.templatesInclude', []);
                $pagesIncludeList     = option('presprog.meta.sitemap.pagesInclude', []);
                $pagesExcludeList     = option('presprog.meta.sitemap.pagesExclude', []);

                $excludeListPattern = '!^(?:' . implode('|', $pagesExcludeList) . ')$!i';

                $cache   = kirby()->cache('pages');
                $cacheId = (kirby()->multilang()) ? 'sitemap.' . kirby()->language()->code() . '.xml' : 'sitemap.xml';

                if (!$sitemap = $cache->get($cacheId, [])) {

                    $sitemap[] = '<?xml version="1.0" encoding="UTF-8"?>';
                    $sitemap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

                    foreach (site()->index() as $item) {

                        if (in_array($item->intendedTemplate()->name(), $templatesIncludeList) === false && in_array($item->id(), $pagesIncludeList) === false) {
                            continue;
                        }

                        if (preg_match($excludeListPattern, $item->id())) {
                            continue;
                        }

                        if (method_exists($item, 'excludeFromSitemap') && $item->excludeFromSitemap()) {
                            continue;
                        }

                        $meta = $item->meta();

                        $sitemap[] = '<url>';
                        $sitemap[] = '  <loc>' . Xml::encode($item->url()) . '</loc>';
                        $sitemap[] = '  <priority>' . number_format($meta->priority(), 1, '.', '') . '</priority>';
                        $sitemap[] = '</url>';
                    }

                    $sitemap[] = '</urlset>';
                    $sitemap   = implode(PHP_EOL, $sitemap);

                    $cache->set($cacheId, $sitemap);
                }

                return new Response($sitemap, 'application/xml');
            },
        ],
    ],

    'translations' => [
        'en' => Yaml::decode(F::read(__DIR__ . '/translations/en.yml')),
        'de' => Yaml::decode(F::read(__DIR__ . '/translations/de.yml')),
    ],

]);
