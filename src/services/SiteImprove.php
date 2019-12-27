<?php
/**
 * Craft Ping Siteimprove plugin for Craft CMS 3.x
 *
 * Pings Siteimprove with this URL
 *
 * @link      triplenerdscore.net
 * @copyright Copyright (c) 2019 tripleNERDscore
 */

namespace triplenerdscore\craftpingsiteimprove\services;

use triplenerdscore\craftpingsiteimprove\CraftPingSiteimprove;

use Craft;
use craft\base\Component;
use GuzzleHttp\Client;

/**
 * SiteImprove Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    tripleNERDscore
 * @package   CraftPingSiteimprove
 * @since     1.0.0
 */
class SiteImprove extends Component
{

    private const BASE_URL = 'https://my2.siteimprove.com';

    private const TOKEN_REQUEST_URI = '/auth/token';

    // site_id - Id for specific site
    // url - Url of the page
    private const CONTENT_CHECK_URI = '/sites/{site_id}/content/check/page';

    // site_id - Id for specific site
    private const SITE_CRAWL_URI = '/sites/{site_id}/content/crawl';

    public $token;

    public $headers = [
        'Accept' => 'application/json',
    ];

    private $client;

    public function __construct() {

        $this->client = new Client(['base_uri' => self::BASE_URL]);

        $response = $this->client->get(self::TOKEN_REQUEST_URI, [
            'headers' => $this->headers,
            'http_errors' => false,
        ]);

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        if($statusCode == 200){

            return $data['data'];

        }

        $this->token = $json->token;

    }

    public function ping($site, $url)
    {

        $uri = str_replace('{site_id}', $site, self::CONTENT_CHECK_URI);

        $response = $this->client->post(
            $uri,
            [
                'site_id'   => $site,
                'url'       => $url,
            ],
            [
                'headers' => $this->headers,
                'http_errors' => false,
            ]
        );

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        if($statusCode == 200){

            return $data['data'];

        }

        return false;
    }

    public function crawlSite($site)
    {

        $uri = str_replace('{site_id}', $site, self::SITE_CRAWL_URI);

        $response = $this->client->post(
            $uri,
            [
                'site_id'   => $site,
                'url'       => $url,
            ],
            [
                'headers' => $this->headers,
                'http_errors' => false,
            ]
        );

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        if($statusCode == 200){

            return $data['data'];

        }

        return false;

    }

    public function crawlStatus($site)
    {

        $uri = str_replace('{site_id}', $site, self::SITE_CRAWL_URI);

        $response = $this->client->gett(
            $uri,
            [
                'headers' => $this->headers,
                'http_errors' => false,
            ]
        );

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        if($statusCode == 200){

            return $data['data'];

        }

        return false;

    }
}
