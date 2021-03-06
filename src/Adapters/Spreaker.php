<?php

namespace Embed\Adapters;

use Embed\Utils;
use Embed\Http\Response;
use Embed\Http\Uri;

/**
 * Adapter to get the embed code from spreaker.com.
 */
class Spreaker extends Webpage implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public static function check(Response $response)
    {
        return $response->isValid() && $response->getUri()->match([
            'www.spreaker.com/*',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        $dom = $this->getResponse()->getHtmlContent();

        foreach ($dom->getElementsByTagName('a') as $a) {
            if ($a->hasAttribute('data-episode_id')) {
                $id = (int) $a->getAttribute('data-episode_id');

                if ($id) {
                    $uri = Uri::create('https://www.spreaker.com/embed/player/standard')
                        ->withQueryParameters([
                            'autoplay' => 'false',
                            'episode_id' => $id,
                        ]);

                    return Utils::iframe($uri, $this->width, $this->height, 'min-width:400px;border:none;overflow:hidden;');
                }

                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return '100%';
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return 131;
    }
}
