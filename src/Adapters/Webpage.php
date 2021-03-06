<?php

namespace Embed\Adapters;

use Embed\Http\Response;
use Embed\Providers;

/**
 * Adapter to provide all information from any webpage.
 */
class Webpage extends Adapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public static function check(Response $response)
    {
        return $response->isValid();
    }

    /**
     * {@inheritdoc}
     */
    protected function init()
    {
        $this->providers = [
            'oembed' => new Providers\OEmbed($this),
            'opengraph' => new Providers\OpenGraph($this),
            'twittercards' => new Providers\TwitterCards($this),
            'dcterms' => new Providers\Dcterms($this),
            'sailthru' => new Providers\Sailthru($this),
            'html' => new Providers\Html($this),
        ];
    }
}
