<?php
/**
 * Craft Ping Siteimprove plugin for Craft CMS 3.x
 *
 * Craft Ping Siteimprove
 *
 * @link      triplenerdscore.net
 * @copyright Copyright (c) 2019 tripleNERDscore
 */

namespace triplenerdscore\craftpingsiteimprove\models;

use triplenerdscore\craftpingsiteimprove\CraftPingSiteimprove;

use Craft;
use craft\base\Model;

/**
 * CraftPingSiteimprove Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    tripleNERDscore
 * @package   CraftPingSiteimprove
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $apiKey = '';
    public $userName = '';
    public $siteId = '';
    public $useCSRF = true;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            [
                'apiKey', 'string'
            ],
            [
                'userName', 'string'
            ],
            [
                'siteId', 'string'
            ],
            [
                'useCSRF', 'boolean'
            ],
        ];
    }
}
