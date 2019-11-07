<?php
/**
 * Request component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\request;

use DOMDocument;
use DOMNode;
use DOMXPath;
use NovemBit\i18n\component\request\exceptions\RequestException;
use NovemBit\i18n\component\translation\interfaces\Translator;
use NovemBit\i18n\component\translation\type\interfaces\HTML;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\URL;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\Module;

/**
 * Request component main class.
 *
 * # Meaning of Request component
 * It make easy to make requests flexible.
 * Determine type of received request.
 * Then provide translation for current type of content.
 *
 * > Using Translation component to translate received buffer content.
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Module $context
 * */
class Request extends Component implements interfaces\Request
{
    /**
     * Translation component
     *
     * @var Translation
     * */
    private $_translation;

    /**
     * Main content language
     *
     * @var string
     * */
    private $_from_language;

    /**
     * Languages of URL
     *
     * @var string
     * */
    private $_language;

    /**
     * Country name
     *
     * @var string
     * */
    private $_country;

    /**
     * Region name
     *
     * @var string
     * */
    private $_region;

    /**
     * Ready status
     *
     * @var bool
     * */
    private $_ready = false;

    /**
     * Language of Referer
     *
     * @var string
     * */
    private $_referer_language;

    /**
     * Original Destination (REQUEST_URI)
     *
     * @var string
     * */
    private $_destination;

    /**
     * Original Referer (HTTP_REFERER)
     *
     * @var string
     * */
    private $_referer;

    /**
     * Source url
     *
     * @var string
     * */
    private $_source_url;

    /**
     * Referer Source url
     *
     * @var string
     * */
    private $_referer_source_url;

    /**
     * Translations of url
     *
     * @var array
     * */
    private $_url_translations;

    /**
     * Editor urls
     *
     * @var array
     * */
    private $_editor_url_translations = [];

    /**
     * Translations of referer
     *
     * @var array
     * */
    private $_referer_translations;

    /**
     * Editor status (enabled/disabled)
     *
     * @var bool
     * */
    private $_is_editor = false;

    /**
     * Allow to use editor
     *
     * @var bool
     * */
    public $allow_editor = true;

    /**
     * Editor query argument key
     *
     * @var string
     * */
    public $editor_query_key = "editor";

    /**
     * Callback exclusions
     * If Callback returns true then current page
     * must be skipped
     *
     * ```php
     * function ($request) {
     *   if (  is_admin() && !wp_doing_ajax()
     *       && (isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] != 'wp-login.php')
     *   ) {
     *      return true;
     *   }
     *   return false;
     * }
     * ```
     *
     * @var array
     * */
    public $exclusions = [];

    /**
     * Page not found callback function
     * Trigger when page not found
     *
     * ```php
     * 'request' => [
     *   ...
     *
     *   'page_not_found_callback' => function($request){
     *       echo "404 page not found";
     *       die;
     *   },
     *
     *   ...
     * ]
     * ```
     *
     * @var callable
     * */
    public $on_page_not_found;

    /**
     * Custom translation level
     * Priority of custom translation
     * Min - 0
     * Max - 999
     *
     * @var int
     * */
    public $custom_translation_level = 1;

    /**
     * Custom colors for editor to mark each level of translation
     *
     * @var array
     * */
    public $custom_translation_level_colors = [
        0 => 'orange',
        1 => '#62c800',
        2 => '#4fa000',
        3 => '#2d5b00'
    ];

    /**
     * Get request referer source url
     *
     * @return string
     */
    public function getRefererSourceUrl(): ?string
    {
        return $this->_referer_source_url;
    }

    /**
     * Set request referer source url
     *
     * @param string $_referer_source_url Referer source url
     *
     * @return void
     */
    public function setRefererSourceUrl(?string $_referer_source_url): void
    {
        $this->_referer_source_url = $_referer_source_url;
    }

    /**
     * Get Referer translations
     *
     * @return array
     */
    public function getRefererTranslations(): ?array
    {
        return $this->_referer_translations;
    }

    /**
     * Set Referer translations
     *
     * @param array $_referer_translations Referer translations
     *
     * @return void
     */
    public function setRefererTranslations(array $_referer_translations): void
    {
        $this->_referer_translations = $_referer_translations;
    }

    /**
     * Get Source Url from translate
     * Using ReTranslate method of Translation
     *
     * @param string $translate Translated url
     * @param string $to_language Language of translated string
     * @param string $country Country name
     * @param string|null $region Region
     *
     * @return string|null
     */
    private function _getSourceUrlFromTranslate(
        string $translate,
        string $to_language,
        ?string $country,
        ?string $region
    ): ?string {

        $re_translate = $this->context->translation
            ->setLanguages([$to_language])
            ->setCountry($country)
            ->setRegion($region)
            ->url
            ->reTranslate([$translate]);
        if (isset($re_translate[$translate])) {
            return $re_translate[$translate];
        }

        return null;
    }

    /**
     * Prepare Destination to finding source
     *
     * @return bool
     */
    private function _prepareDestination(): bool
    {
        $dest = '/' . trim($_SERVER['REQUEST_URI'], '/');
        $dest = URL::removeQueryVars(
            $dest,
            $this->context->languages->getLanguageQueryKey()
        );

        $dest = URL::removeQueryVars(
            $dest,
            $this->context->prefix . "-" . $this->editor_query_key
        );

        $dest = urldecode($dest);
        $this->_setDestination($dest);

        return true;
    }

    /**
     * Get request referer
     *
     * @return string
     */
    public function getReferer(): ?string
    {
        return $this->_referer;
    }

    /**
     * Set request referer
     *
     * @param string $_referer Referer url
     *
     * @return void
     */
    public function setReferer(string $_referer): void
    {
        $this->_referer = $_referer;
    }

    /**
     * Prepare Referer
     * To create response document
     *
     * @return bool
     */
    private function _prepareReferer(): bool
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->_prepareRefererLanguage();

            $referer = trim($_SERVER['HTTP_REFERER'], '/');
            $referer = URL::removeQueryVars(
                $referer,
                $this->context->languages->getLanguageQueryKey()
            );
            $referer = urldecode($referer);
            $this->setReferer($referer);

            $this->_prepareRefererSourceUrl();
        }

        return true;
    }

    /**
     * Prepare Referer source url
     * To create response document
     *
     * @return bool
     */
    private function _prepareRefererSourceUrl(): bool
    {
        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->getRefererLanguage() == $this->getFromLanguage()) {

            $this->setRefererSourceUrl($this->getReferer());

        } else {
            /*
            * Set referrer source origin URL
            * */
            $this->setRefererSourceUrl(
                $this->_getSourceUrlFromTranslate(
                    $this->getReferer(),
                    $this->getRefererLanguage(),
                    $this->getCountry(),
                    $this->getRegion()
                )
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['HTTP_REFERER'] = $this->getRefererSourceUrl();

        return true;
    }

    /**
     * Prepare Source url
     * To create response document
     *
     * @return bool
     * @throws RequestException
     */
    private function _prepareSourceUrl(): bool
    {
        /*
         * If current language is from_language
         * Then translate current url for all languages
         * */
        if ($this->getLanguage() == $this->getFromLanguage()
            || $this->getDestination() == '/'
            || !$this->getTranslation()->url->isPathTranslation()
        ) {

            $this->_setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->setCountry($this->getCountry())
                    ->url->translate([$this->getDestination()])
                [$this->getDestination()] ?? null
            );

            /*
             * Set source origin URL
             * */
            $this->_setSourceUrl($this->getDestination());
        } else {
            /*
            * Set source origin URL
            * */
            $this->_setSourceUrl(
                $this->_getSourceUrlFromTranslate(
                    $this->getDestination(),
                    $this->getLanguage(),
                    $this->getCountry(),
                    $this->getRegion()
                )
            );

            /*
             * Set current url all translations
             * */
            $this->_setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->setCountry($this->getCountry())
                    ->url->translate([$this->getSourceUrl()])[$this->getSourceUrl()]
                ?? null
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['REQUEST_URI'] = $this->getSourceUrl() ?? '/';

        /**
         * Handling 404 action page
         * Running page_not_found callable
         * */
        if ($this->getDestination() != null && $this->getSourceUrl() == null) {

            if (isset($this->on_page_not_found)
                && is_callable($this->on_page_not_found)
            ) {
                call_user_func($this->on_page_not_found, $this);
                return false;

            } else {
                throw new  RequestException("404 Not Found", 404);
            }
        }

        return true;
    }

    /**
     * Check exclusions array and expand
     * Callbacks and variables
     *
     * @return bool
     */
    private function _isExclusion(): bool
    {
        foreach ($this->exclusions as $exclusion) {
            if (is_callable($exclusion)) {
                return call_user_func($exclusion, $this);
            } else {
                return $exclusion;
            }
        }
        return false;
    }

    /**
     * Prepare all components to start request translation
     * And to create response document
     *
     * @return boolean
     *
     * @throws RequestException
     */
    private function _prepare(): bool
    {
        if ($this->_isExclusion()) {
            return false;
        }

        $this->_setTranslation($this->context->translation);
        $this->setFromLanguage($this->context->languages->getFromLanguage());

        return $this->_prepareLanguage()
            && $this->_prepareRegion()
            && $this->_prepareCountry()
            && $this->_prepareDestination()
            && $this->_prepareSourceUrl()
            && $this->_prepareReferer();

    }

    /**
     * Get Referer language
     *
     * @return string
     */
    public function getRefererLanguage(): string
    {
        return $this->_referer_language;
    }

    /**
     * Set Referer Language
     *
     * @param string $_referer_language Referer language
     *
     * @return void
     */
    public function setRefererLanguage(string $_referer_language): void
    {
        $this->_referer_language = $_referer_language;
    }

    /**
     * Prepare Referer language
     *
     * @return bool
     */
    private function _prepareRefererLanguage(): bool
    {

        $_SERVER["ORIG_HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages
            ->getLanguageFromUrl($_SERVER["HTTP_REFERER"]);

        /**
         * If language does not exists in *url*
         * */
        if ($language == null) {
            $language = $this->context->languages->getDefaultLanguage(
                $_SERVER['HTTP_HOST'] ?? null
            );
        }

        /*
         * Setting current instance language
         * */
        $this->setRefererLanguage($language);

        /*
         * Remove Language from URI
         * */
        $this->_removeLanguageFromURI($_SERVER['HTTP_REFERER']);

        return true;
    }

    /**
     * Prepare language
     *
     * @return bool
     */
    private function _prepareLanguage(): bool
    {
        /**
         * Check if tried to access from cli
         * */
        if (!isset($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $_SERVER['ORIG_REQUEST_URI'] = $_SERVER['REQUEST_URI'];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages
            ->getLanguageFromUrl($_SERVER['REQUEST_URI']);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->context
                ->languages
                ->getDefaultLanguage($_SERVER['HTTP_HOST'] ?? null);
        }

        /*
         * Setting current instance language
         * */
        $this->_setLanguage($language);

        /*
         * Remove Language from URI
         * */
        $this->_removeLanguageFromURI($_SERVER['REQUEST_URI']);

        return true;
    }

    /**
     * Get current country
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->_country;
    }

    /**
     * Set current country
     *
     * @param mixed $country Country name
     *
     * @return void
     */
    private function _setCountry(?string $country): void
    {
        $this->_country = $country;
    }

    /**
     * Set current region
     *
     * @param string|null $region Region name
     *
     * @return void
     */
    private function _setRegion(?string $region): void
    {
        $this->_region = $region;
    }

    /**
     * Get current region
     *
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->_region;
    }

    /**
     * Prepare country
     *
     * @return bool
     */
    private function _prepareCountry(): bool
    {
        $country = $this->context
            ->languages
            ->getDefaultCountry($_SERVER['HTTP_HOST'] ?? null);
        $this->_setCountry($country);
        return true;
    }

    /**
     * Prepare country
     *
     * @return bool
     */
    private function _prepareRegion(): bool
    {
        $country = $this->context
            ->languages
            ->getDefaultRegion($_SERVER['HTTP_HOST'] ?? null);
        $this->_setRegion($country);
        return true;
    }

    /**
     * Remove language from uri string
     *
     * @param string $uri Referenced variable of URI string
     *
     * @return void
     */
    private function _removeLanguageFromURI(&$uri): void
    {
        $parts = parse_url(trim($uri, '/'));
        if (isset($parts['path'])) {
            $path = explode('/', ltrim($parts['path'], '/'));

            if ($this->context->languages->validateLanguage($path[0])) {
                unset($path[0]);
            }
            $parts['path'] = implode('/', $path);
            $new_url = URL::buildUrl($parts);
            $uri = empty($new_url) ? '/' : $new_url;
        }
    }

    public $source_type_map = [];

    private function _getType($source, $content)
    {

        foreach ($this->source_type_map as $pattern => $type) {
            if (preg_match($pattern, $source)) {
                return $type;
            }
        }

        return DataType::getType($content);
    }

    /**
     * Translate buffer of request content
     *
     * @param string $content content of request buffer
     *
     * @return string
     */
    public function translateBuffer(?string $content): ?string
    {
        $status = http_response_code();

        /*
         * If response status is success
         * */
        if (in_array($status, range(200, 299))) {

            $type = $this->_getType($this->getSourceUrl(), $content);

            if ($type !== null) {
                /**
                 * Define type of translator
                 *
                 * @var Translator $translator
                 */
                $translator = $this
                    ->getTranslation()
                    ->setLanguages([$this->getLanguage()])
                    ->setRegion($this->getRegion())
                    ->setCountry($this->getCountry())
                    ->{$type};
                if ($type == "html") {

                    /**
                     * Define type of HTML translator
                     *
                     * @var HTML $translator
                     */

                    $translator->addAfterParseCallback(
                        function (DOMXPath $xpath, DOMDocument $dom) {

                            $head = $xpath->query('//html/head')->item(0);
                            if ($head !== null) {
                                $this->_addMainJavaScriptNode($dom, $head);
                                $this->_addXHRManipulationJavaScript($dom, $head);
                                $this->_addAlternateLinkNodes($dom, $head);
                                if ($this->allow_editor) {
                                    $this->_addEditorAssets($dom, $head);
                                }
                            }
                        }
                    );

                }
                /*
                 * Translate content
                 * */
                $content = $translator
                    ->translate([$content])[$content][$this->getLanguage()] ?? $content;

            }
        }

        return $content;
    }

    /**
     * Save Editor if request is POST and has parameter %prefix%-form
     *
     * @return bool
     */
    private function _editorSave(): bool
    {

        if ($this->isEditor()
            && isset($_POST[$this->context->prefix . '-form'])
        ) {
            $nodes = $_POST[$this->context->prefix . '-form'];

            $result = [];

            foreach ($nodes as $source => $translate) {
                $result[$source][$this->getLanguage()] = $translate;
            }

            /**
             * Save translations
             * With Level *1*
             * And overwrite old values if exists
             * */
            $this->context->translation->text->saveModels(
                $result,
                $this->custom_translation_level,
                true,
                $verbos
            );

            echo json_encode($verbos);

            return true;
        }

        return false;
    }

    /**
     * Start request translation
     *
     * @return void
     * @throws RequestException
     */
    public function start(): void
    {

        if (!$this->_prepare()) {
            return;
        }


        /**
         * If isset editor query key
         * And current language is not equal from language
         * Then set editor status true to initialize editor JavaScript
         * */
        if ($this->allow_editor) {

            /**
             * Adding editor urls
             * */
            if ($this->getUrlTranslations() !== null) {
                foreach ($this->getUrlTranslations() as $language => $url) {
                    if ($language == $this->getFromLanguage()) {
                        continue;
                    }

                    $this->_editor_url_translations[$language] = URL::addQueryVars(
                        $url,
                        sprintf(
                            "%s-%s",
                            $this->context->prefix,
                            $this->editor_query_key
                        ),
                        true
                    );
                }
            }

            if (isset($_GET[$this->context->prefix . '-' . $this->editor_query_key])
                && ($this->getLanguage() != $this->getFromLanguage())
            ) {
                $this->_is_editor = true;

                /**
                 * Enable helper attributes to use for editor
                 *
                 * @see HTML::$_helper_attributes
                 * */
                $this->context->translation->html->setHelperAttributes(true);
            }

            if ($this->_editorSave()) {
                die;
            }
        }

        $this->setReady(true);

        ob_start([$this, 'translateBuffer']);
    }


    /**
     * Get <link rel="alternate"...> tags
     * To add on HTML document <head>
     *
     * @param DOMDocument $dom Document object
     * @param DOMNode $parent Parent element
     *
     * @return void
     */
    private function _addAlternateLinkNodes(DOMDocument $dom, DOMNode $parent): void
    {
        if($this->getUrlTranslations() !==null) {
            foreach ($this->getUrlTranslations() as $language => $translate) {
                $node = $dom->createElement('link');
                $node->setAttribute('rel', 'alternate');
                $node->setAttribute('hreflang', $language);
                $node->setAttribute('href', $translate);
                $parent->appendChild($node);
            }
        }
    }

    /**
     * Get main JS object <script> tag
     * To add on HTML document <head>
     *
     * @param DOMDocument $dom Document object
     * @param DOMNode $parent Parent element
     *
     * @return void
     */
    private function _addMainJavaScriptNode(
        DOMDocument &$dom,
        DOMNode $parent
    ): void {

        $config = json_encode(
            [
                'i18n' => [

                    'current_language' => $this->getLanguage(),

                    'accept_languages' => $this->context->languages
                        ->getAcceptLanguages(true),

                    'language_query_key' => $this->context->languages
                        ->getLanguageQueryKey(),

                    'editor' => [
                        'is_editor' => $this->isEditor(),
                        'query_key' => $this->editor_query_key,
                        'url_translations' => $this->getEditorUrlTranslations()
                    ],

                    'prefix' => $this->context->prefix,

                    'orig_request_uri' => URL::removeQueryVars(
                        $_SERVER['ORIG_REQUEST_URI'],
                        $this->context->prefix . '-' . $this->editor_query_key
                    ),

                    'destination' => $this->getDestination(),
                    'uri' => $this->getSourceUrl(),
                    'orig_referer' => $this->getReferer(),
                    'referer' => $this->getRefererSourceUrl(),
                    'url_translations' => $this->getUrlTranslations(),
                    'referer_translations' => $this->getRefererTranslations(),
                ]
            ]
        );
        $script = "(function() {window.novembit={$config}})()";

        $node = $dom->createElement('script');
        $node->appendChild($dom->createTextNode($script));
        $node->setAttribute('type', 'application/javascript');
        $parent->appendChild($node);

    }

    /**
     * Get Editor JS <script> tag
     * To add on HTML document <head>
     *
     * @param DOMDocument $dom Document object
     * @param DOMNode $parent Parent element
     *
     * @return void
     */
    private function _addEditorAssets(DOMDocument $dom, DOMNode $parent): void
    {
        $script = file_get_contents(__DIR__ . '/assets/js/editor.js');
        $scriptNode = $dom->createElement('script');
        $scriptNode->appendChild($dom->createTextNode($script));
        $scriptNode->setAttribute('type', 'application/javascript');

        $parent->appendChild($scriptNode);

        $css = file_get_contents(__DIR__ . '/assets/css/editor.css');
        $css = str_replace('__PREFIX', $this->context->prefix, $css);
        foreach ($this->custom_translation_level_colors as $level => $color) {
            $css .= sprintf(
                "%s#%s-editor-wrapper .level-%d-bg { background-color: %s; }",
                PHP_EOL,
                $this->context->prefix,
                $level,
                $color
            );
            $css .= sprintf(
                "%s#%s-editor-wrapper .level-%d { color: %s; }",
                PHP_EOL,
                $this->context->prefix,
                $level,
                $color
            );
        }

        $styleNode = $dom->createElement('style');
        $styleNode->appendChild($dom->createTextNode($css));
        $styleNode->setAttribute('type', 'text/css');
        $parent->appendChild($styleNode);

    }

    /**
     * Get XHR(ajax) Manipulation javascript <script> tag
     * To add on HTML document <head>
     *
     * @param DOMDocument $dom Document object
     * @param DOMNode $parent Parent element
     *
     * @return void
     */
    private function _addXHRManipulationJavaScript(
        DOMDocument $dom,
        DOMNode $parent
    ): void {
        $script = file_get_contents(__DIR__ . '/assets/js/xhr.js');
        $node = $dom->createElement('script');
        $node->appendChild($dom->createTextNode($script));
        $node->setAttribute('type', 'application/javascript');
        $parent->appendChild($node);
    }

    /**
     * Get Request Destination
     *
     * @return string
     */
    public function getDestination(): string
    {
        return $this->_destination;
    }

    /**
     * Set Request Destination
     *
     * @param string $destination Destination uri
     *
     * @return void
     */
    private function _setDestination(string $destination): void
    {
        $this->_destination = $destination;
    }

    /**
     * Get Source Url
     *
     * @return string
     */
    public function getSourceUrl(): ?string
    {
        return $this->_source_url;
    }

    /**
     * Set Source Url
     *
     * @param string $source_url Source Url
     *
     * @return void
     */
    private function _setSourceUrl(?string $source_url): void
    {
        $this->_source_url = $source_url;
    }

    /**
     * Get Url translations list
     *
     * @return array
     */
    public function getUrlTranslations(): ?array
    {
        return $this->_url_translations;
    }

    /**
     * Set Url Translations list
     *
     * @param array $url_translations Url Translations list
     *
     * @return void
     */
    private function _setUrlTranslations(?array $url_translations): void
    {
        $this->_url_translations = $url_translations;
    }

    /**
     * Get Request current Language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->_language;
    }

    /**
     * Set Request current language
     *
     * @param string $language Language
     *
     * @return void
     */
    private function _setLanguage($language): void
    {
        $this->_language = $language;
    }

    /**
     * Get Translation Component
     *
     * @return Translation
     */
    public function getTranslation(): Translation
    {
        return $this->_translation;
    }

    /**
     * Set Translation component
     *
     * @param Translation $translation Translation component
     *
     * @return void
     */
    private function _setTranslation(Translation $translation): void
    {
        $this->_translation = $translation;
    }

    /**
     * Get main content language
     *
     * @return string
     */
    public function getFromLanguage(): string
    {
        return $this->_from_language;
    }

    /**
     * Set main content language
     *
     * @param string $from_language Language code
     *
     * @return void
     */
    public function setFromLanguage(string $from_language): void
    {
        $this->_from_language = $from_language;
    }

    /**
     * If is editor mode
     *
     * @return bool
     */
    public function isEditor(): bool
    {
        return $this->_is_editor;
    }

    /**
     * Get editor urls on all allowed languages
     *
     * @return array
     */
    public function getEditorUrlTranslations(): array
    {
        return $this->_editor_url_translations;
    }

    /**
     * If request component ready to use
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return $this->_ready;
    }

    /**
     * Set ready status
     *
     * @param bool $ready Ready status
     *
     * @return void
     */
    public function setReady(bool $ready): void
    {
        $this->_ready = $ready;
    }
}
