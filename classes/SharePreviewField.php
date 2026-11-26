<?php declare(strict_types=1);

namespace PresProg\KirbyMeta;

use Kirby\Cms\File;
use Kirby\Form\Field\DisplayField;

class SharePreviewField extends DisplayField
{
    public function api(): array
    {
        $field = $this;

        return [
            [
                'pattern' => '',
                'method'  => 'GET',
                'action'  => fn () => $field->preview(),
            ],
        ];
    }

    public function preview(): array
    {
        $page = $this->model();
        $options = PageMetaOptions::fromOptions();
        $meta = PageMeta::for($page, $options);

        return [
            'siteTitle'       => $page->site()->title()->toString(),
            'siteDescription' => $page->site()->metaDescription()->toString(),
            'siteImage'       => $this->imageUrl($meta->siteImage(), $options),
            'pageTitle'       => $page->title()->toString(),
            'pageUrl'         => $page->url(),
            'pageImage'       => $this->imageUrl($meta->pageImage(), $options),
            'pageModelImage'  => $this->imageUrl($meta->pageModelImage(), $options),
            'metaOptions'     => get_object_vars($options),
        ];
    }

    private function imageUrl(?File $image, PageMetaOptions $options): string
    {
        return $image ? $image->crop($options->ogImageWidth, $options->ogImageHeight)->url() : '';
    }
}
