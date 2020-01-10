<?php
/**
 * Craft Ping Siteimprove plugin for Craft CMS 3.x
 *
 * Test
 *
 * @link      triplenerdscore.net
 * @copyright Copyright (c) 2019 tripleNERDscore
 */

namespace triplenerdscore\craftpingsiteimprove\controllers;

use \craftpingsiteimprove\CraftPingSiteimprove;

use Craft;
use craft\web\Controller;
use triplenerdscore\craftpingsiteimprove\services\SiteImprove;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    tripleNERDscore
 * @package   CraftPingSiteimprove
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['action-index'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/craft-ping-siteimprove/default
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $service = new SiteImprove();

        // Get URL from POST request
        $request = Craft::$app->getRequest();

        $url = $request->getParam('url');

        $siteId = \triplenerdscore\craftpingsiteimprove\CraftPingSiteimprove::getInstance()->getSettings()->siteId;

        $result = $service->ping($siteId, $url);

        return $this->returnJson(
                    [
                        'result' => $result,
                    ]
                );

    }

}
