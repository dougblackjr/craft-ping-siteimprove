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
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

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

    private const BASE_URL = 'https://api.siteimprove.com';

    private const TOKEN_REQUEST_URI = '/v2/auth/token';

    // site_id - Id for specific site
    // url - Url of the page
    private const CONTENT_CHECK_URI = '/v2/sites/{site_id}/content/check/page';

    // site_id - Id for specific site
    private const SITE_CRAWL_URI = '/v2/sites/{site_id}/content/crawl';

    public $token;
    public $userId;
    public $apiKey;

    protected $container;
        
    protected $history;
    
    protected $stack;

    public $headers = [
        'Accept' => 'application/json',
    ];

    private $client;

    public function __construct() {

        $this->container = [];

        $this->history = Middleware::history($this->container);

        $this->stack = HandlerStack::create();
        
        // Add the history middleware to the handler stack.
        $this->stack->push($this->history);

        $this->client = new Client(
            [
                'base_uri'  => self::BASE_URL,
                'handler'   => $this->stack,
            ]
        );

        $this->userId = \triplenerdscore\craftpingsiteimprove\CraftPingSiteimprove::getInstance()->getSettings()->userName;
        $this->apiKey = \triplenerdscore\craftpingsiteimprove\CraftPingSiteimprove::getInstance()->getSettings()->apiKey;

    }

    public function ping($site, $url)
    {

        $uri = str_replace('{site_id}', $site, self::CONTENT_CHECK_URI);

        try {
            $response = $this->client->post(
                "{$uri}?url={$url}",
                [
                    'json'  => [
                        'site_id'   => $site,
                        'url'       => $url,
                    ],
                    'headers' => $this->headers,
                    'http_errors' => false,
                    'auth'  => [ $this->userId, $this->apiKey ]
                ]
            );
        } catch (\ErrorException $e) {
            return [
                'success'   => false,
                'error'     => $e->getMessage(),
            ];
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [
                'success'   => false,
                'error'     => $e->getMessage(),
            ];
        }

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        return $data;

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
                'auth'  => [ $this->userId, $this->apiKey ],
            ]
        );

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        return $statusCode == 200;

    }

    public function crawlStatus($site)
    {

        $uri = str_replace('{site_id}', $site, self::SITE_CRAWL_URI);

        $response = $this->client->gett(
            $uri,
            [
                'headers' => $this->headers,
                'http_errors' => false,
                'auth'  => [ $this->userId, $this->apiKey ],
            ]
        );

        $statusCode = $response->getStatusCode();
        
        $data = json_decode($response->getBody(), true);

        return $statusCode == 200;

    }

}
