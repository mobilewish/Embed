<?php

namespace Embed\Adapters;

use Embed\Http\Response;
use Embed\Utils;
use Embed\Providers\Api;

/**
 * Adapter provider more information from google maps and google drive.
 */
class Google extends Webpage implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public static function check(Response $response)
    {
        return $response->isValid() && $response->getUri()->match([
            'maps.google.*',
            'www.google.*/maps*',
            'calendar.google.com/calendar/*',
            'drive.google.com/file/*/view',
            'plus.google.com/*/posts/*',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        parent::init();

        if ($this->getResponse()->getUri()->match('*/maps/*')) {
            $this->providers = ['google' => new Api\GoogleMaps($this)] + $this->providers;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $this->width = null;
        $this->height = null;

        $uri = $this->getResponse()->getUri();

        if ($uri->getHost() === 'plus.google.com') {
            return '<script src="https://apis.google.com/js/plusone.js" type="text/javascript"></script>'
                .'<div class="g-post" data-href="'.$uri.'"></div>';
        }

        if ($uri->getHost() === 'calendar.google.com') {
            return Utils::iframe($uri);
        }

        if (isset($this->providers['google'])) {
            return $this->providers['google']->getCode();
        }

        return Utils::iframe($uri
            ->withDirectoryPosition(3, 'preview')
            ->withQueryParameters([]));
    }

    /**
     * {@inheritdoc}
     */
    public function getImagesUrls()
    {
        if ($this->getResponse()->getUri()->getHost() === 'plus.google.com') {
            return parent::getImagesUrls();
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderName()
    {
        if ($this->getResponse()->getUri()->getHost() === 'plus.google.com') {
            return 'Google Plus';
        }

        return parent::getProviderName();
    }
}
