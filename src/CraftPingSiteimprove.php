<?php
/**
 * Craft Ping Siteimprove plugin for Craft CMS 3.x
 *
 * Pings Siteimprove with this URL
 *
 * @link      triplenerdscore.net
 * @copyright Copyright (c) 2019 tripleNERDscore
 */

namespace triplenerdscore\craftpingsiteimprove;

use triplenerdscore\craftpingsiteimprove\services\SiteImprove as SiteImproveService;
use triplenerdscore\craftpingsiteimprove\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    tripleNERDscore
 * @package   CraftPingSiteimprove
 * @since     1.0.0
 *
 * @property  SiteImproveService $siteImprove
 */
class CraftPingSiteimprove extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * CraftPingSiteimprove::$plugin
     *
     * @var CraftPingSiteimprove
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * CraftPingSiteimprove::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // We were just installed
                }
            }
        );

        Craft::$app->getView()->hook('cp.entries.edit.details', function(array &$context) {
            /** @var EntryModel $entry **/
            $entry = $context['entry'];

            $url = $entry->url;

            $string = '<div class="meta"><div class="data"><h5 class="heading">Ping Site Improve</h5><a id="craft-ping-site-improve" data-href="' . $url . '" class="btn">Ping</a><script>' . $this->buildAjax($url) . '</script></div></div>';

            return $string;
        });

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'craft-ping-siteimprove',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================
    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate(
            'craft-ping-siteimprove/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    protected function buildAjax($url) {

        $ajax = <<<AJAX
const cpsi = document.getElementById("craft-ping-site-improve")

cpsi.addEventListener('click', function(event) {

    event.preventDefault();

    if(cpsi.disabled) return false;

    let url = cpsi.dataset.href,
        csrfName = window.Craft.csrfTokenName
        csrfToken = window.Craft.csrfTokenValue;

    console.log('url', url)

    cpsi.innerHTML = 'Submitting...';

    const xhr = new XMLHttpRequest();

    var params = new FormData();
    params.append('url', url);
    params.append(csrfName, csrfToken);

    xhr.open('POST', '/actions/craft-ping-siteimprove/default');
    xhr.onload = function () {
        console.log(this.responseText);
        cpsi.innerHTML = 'Sent!';
        cpsi.disabled = true;
    };
    xhr.send((params));
})
AJAX;

    return $ajax;
    }

}
