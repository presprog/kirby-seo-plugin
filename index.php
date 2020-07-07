<?php

use Kirby\Cms\App as Kirby;
use Kirby\Http\Response;
use Kirby\Toolkit\Xml;
use PresProg\KirbyMeta\PageMeta;

Kirby::plugin('presprog/meta', [

    'blueprints' => [
        'tabs/meta' => __DIR__ . '/blueprints/tabs/meta.yml',
        'fields/companyInfo' => __DIR__ . '/blueprints/fields/companyInfo.yml'
    ],

    'options' => [
        'templatesInclude' => [],
        'pagesInclude' => [],
        'pagesExclude' => [],
    ],

    'routes' => [
        [
            'pattern' => 'robots.txt',
            'method' => 'ALL',
            'action' => function () {
                $robots = 'User-agent: *' . PHP_EOL;
                $robots .= 'Allow: /' . PHP_EOL;
                $robots .= 'Sitemap: ' . url('sitemap.xml');

                return kirby()
                    ->response()
                    ->type('text')
                    ->body($robots);
            }
        ],
        [
            'pattern' => 'sitemap.xml',
            'action' => function () {
                $templatesWhitelist = option('kirby.meta.templatesInclude', []);
                $pagesWhitelist = option('kirby.meta.pagesInclude', []);
                $pagesBlacklist = option('kirby.meta.pagesExclude', []);

                $blacklistPattern = '!^(?:' . implode('|', $pagesBlacklist) . ')$!i';

                $cache = kirby()->cache('pages');
                $cacheId = 'sitemap.xml';

                if (!$sitemap = $cache->get($cacheId, [])) {

                    $sitemap[] = '<?xml version="1.0" encoding="UTF-8"?>';
                    $sitemap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

                    foreach (site()->index() as $item) {

                        if (in_array($item->intendedTemplate()->name(), $templatesWhitelist) === false && in_array($item->id(), $pagesWhitelist) === false) {
                            continue;
                        }

                        if (preg_match($blacklistPattern, $item->id())) {
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
                    $sitemap = implode(PHP_EOL, $sitemap);

                    $cache->set($cacheId, $sitemap);
                }

                return new Response($sitemap, 'application/xml');
            }
        ]
    ],

    'pageMethods' => [
        'meta' => function () {
            return new PageMeta($this);
        }
    ]
]);
