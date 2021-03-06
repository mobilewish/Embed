<?php

namespace Embed\Providers\OEmbed;

use Embed\Adapters\AdapterInterface;
use Embed\Http\Response;
use Embed\Http\Uri;

class Iframely implements EndPointInterface
{
    private $response;
    private $key;

    /**
     * {@inheritdoc}
     */
    public static function create(AdapterInterface $adapter)
    {
        $key = $adapter->getConfig('oembed[iframely_key]');

        if (!empty($key)) {
            return new static($adapter->getResponse(), $key);
        }
    }

    /**
     * Constructor.
     *
     * @param Response $response
     * @param string   $key
     */
    private function __construct(Response $response, $key)
    {
        $this->response = $response;
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndPoint()
    {
        return Uri::create('http://open.iframe.ly/api/oembed')
                ->withQueryParameters([
                    'url' => (string) $this->response->getUri(),
                    'format' => 'json',
                    'api_key' => $this->key,
                ]);
    }
}
