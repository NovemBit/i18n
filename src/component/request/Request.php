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

use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;
use Psr\SimpleCache\InvalidArgumentException;
use Doctrine\DBAL\ConnectionException;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Exception;
use NovemBit\i18n\component\request\exceptions\RequestException;
use NovemBit\i18n\component\translation\interfaces\Translator;
use NovemBit\i18n\component\translation\type\interfaces\HTML;
use NovemBit\i18n\component\translation\interfaces\Translation;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\helpers\Environment;
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
    private $translation;

    /**
     * Main content language
     *
     * @var string
     * */
    private $from_language;

    /**
     * Languages of URL
     *
     * @var string
     * */
    private $language;

    /**
     * Default language for current host
     *
     * @var string
     * */
    private $default_language;

    /**
     * Country name
     *
     * @var string
     * */
    private $country;

    /**
     * Region name
     *
     * @var string
     * */
    private $region;

    /**
     * Ready status
     *
     * @var bool
     * */
    private $ready = false;

    /**
     * Language of Referer
     *
     * @var string
     * */
    private $referer_language;

    /**
     * Original Destination (REQUEST_URI)
     *
     * @var string
     * */
    private $destination;

    /**
     * Original Referer (HTTP_REFERER)
     *
     * @var string
     * */
    private $referer;

    /**
     * Source url
     *
     * @var string
     * */
    private $source_url;

    /**
     * Referer Source url
     *
     * @var string
     * */
    private $referer_source_url;

    /**
     * Translations of url
     *
     * @var array
     * */
    private $url_translations;

    /**
     * Editor urls
     *
     * @var array
     * */
    private $editor_url_translations = [];

    /**
     * Translations of referer
     *
     * @var array
     * */
    private $referer_translations;

    /**
     * Editor status (enabled/disabled)
     *
     * @var bool
     * */
    private $is_editor = false;

    /**
     * Orig request uri
     *
     * @var string
     * */
    private $orig_request_uri;

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
     * Callback function to run after editor save
     *
     * @var callable
     * */
    public $editor_after_save_callback;

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
     * HTTP methods for translate
     *
     * @var array
     * */
    public $accept_request_methods = ['GET', 'POST'];

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
     * Default `HTTP_HOST`
     * This property not required but recommended,
     * To prevent page content translations for default domain with default language
     *
     * @var string
     * */
    public $default_http_host;

    /**
     * If true then redirect non translated urls to translated url
     *
     * @example https://test.com/fr/shop redirect to https://test.com/fr/boutique
     * @example https://test.fr/shop redirect to https://test.fr/boutique
     *
     * @var bool
     * */
    public $restore_non_translated_urls = true;

    /**
     * Redirect from https://test.com/fr/ to https://test.fr
     *
     * @var bool
     * */
    public $localization_redirects = true;

    /**
     * Get allowed request methods
     *
     * @return array
     */
    public function getAcceptRequestMethods(): array
    {
        return $this->accept_request_methods;
    }

    /**
     * Get whole verbose of request processing
     *
     * @return array
     */
    public function getVerbose(): array
    {
        return $this->verbose;
    }

    /**
     * @return bool
     */
    public function isAllowEditor(): bool
    {
        return $this->allow_editor;
    }

    /**
     * @return string
     */
    public function getDefaultHttpHost(): string
    {
        return $this->default_http_host;
    }

    /**
     * Get orig request uri
     *
     * @return string
     */
    public function getOrigRequestUri(): string
    {
        return $this->orig_request_uri;
    }

    /**
     * Set Original request uri
     *
     * @param string $orig_request_uri Original request uri
     *
     * @return void
     */
    private function setOrigRequestUri(string $orig_request_uri): void
    {
        $this->orig_request_uri = $orig_request_uri;
    }

    /**
     * Get request referer source url
     *
     * @return string
     */
    public function getRefererSourceUrl(): ?string
    {
        return $this->referer_source_url;
    }

    /**
     * Set request referer source url
     *
     * @param string $_referer_source_url Referer source url
     *
     * @return void
     */
    private function setRefererSourceUrl(?string $_referer_source_url): void
    {
        $this->referer_source_url = $_referer_source_url;
    }

    /**
     * Get Referer translations
     *
     * @return array
     */
    public function getRefererTranslations(): ?array
    {
        return $this->referer_translations;
    }

    /**
     * Set Referer translations
     *
     * @param array $_referer_translations Referer translations
     *
     * @return void
     */
    private function setRefererTranslations(array $_referer_translations): void
    {
        $this->referer_translations = $_referer_translations;
    }

    /**
     * Get Source Url from translate
     * Using ReTranslate method of Translation
     *
     * @param string $translate Translated url
     * @param string $to_language Language of translated string
     *
     * @return string|null
     * @throws UnsupportedLanguagesException
     */
    private function getSourceUrlFromTranslate(
        string $translate,
        string $to_language
    ): ?string {
        $re_translate = $this->getTranslation()
            ->setLanguages([$to_language])
            ->url
            ->reTranslate([$translate]);

        if (isset($re_translate[$translate])) {
            return $re_translate[$translate];
        }

        return null;
    }

    /**
     * Prepare Destination to find source
     *
     * @return bool
     */
    private function prepareDestination(): bool
    {
        $request_uri = Environment::server('REQUEST_URI');
        if ($request_uri === null) {
            return false;
        }

        $dest = '/' . trim($request_uri, '/');
        $dest = URL::removeQueryVars(
            $dest,
            $this->context->localization->languages->getLanguageQueryKey()
        );

        $dest = URL::removeQueryVars(
            $dest,
            $this->context->prefix . "-" . $this->editor_query_key
        );

        $dest = urldecode($dest);

        $this->setDestination($dest);

        /**
         * Make sure the localized url is equals to current
         * Destination url, if not then redirects to localized url
         * */
        if (
            $this->localization_redirects
            && !in_array(Environment::server('HTTP_HOST'), $this->context->localization->getGlobalDomains())
        ) {
            $localized_url = $this->context->localization->languages->addLanguageToUrl(
                $dest,
                $this->getLanguage(),
                Environment::server('HTTP_HOST')
            );

            $localized_url_parts = parse_url($localized_url);

            /**
             * 1. If in localized url parts exists hosts
             * 2. If current host is not default host
             * Because default host should be used as global domain
             * And support all languages
             * 3. If localized url host is not equal server current http host
             * */
            if (
                isset($localized_url_parts['host'])
                && $localized_url_parts['host'] !== Environment::server('HTTP_HOST')
            ) {
                $this->redirect($localized_url);
            }
        }

        return true;
    }

    /**
     * Get request referer
     *
     * @return string
     */
    public function getReferer(): ?string
    {
        return $this->referer;
    }

    /**
     * Set request referer
     *
     * @param string $_referer Referer url
     *
     * @return void
     */
    private function setReferer(string $_referer): void
    {
        $this->referer = $_referer;
    }

    /**
     * Prepare Referer
     * To create response document
     *
     * @return bool
     * @throws UnsupportedLanguagesException
     */
    private function prepareReferer(): bool
    {
        $http_referer = Environment::server('HTTP_REFERER');
        if ($http_referer !== null) {
            $this->prepareRefererLanguage();

            $referer = trim($http_referer, '/');
            $referer = URL::removeQueryVars(
                $referer,
                $this->context->localization->languages->getLanguageQueryKey()
            );

            $referer = urldecode($referer);

            $this->setReferer($referer);

            $this->prepareRefererSourceUrl();
        }

        return true;
    }

    /**
     * Prepare Referer source url
     * To create response document
     *
     * @return bool
     * @throws UnsupportedLanguagesException
     */
    private function prepareRefererSourceUrl(): bool
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
                $this->getSourceUrlFromTranslate(
                    $this->getReferer(),
                    $this->getRefererLanguage()
                )
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        Environment::server('HTTP_REFERER', $this->getRefererSourceUrl());

        return true;
    }

    /**
     * Prepare Source url
     * Set original urls that already translated and cached on DB
     *
     * @return bool
     * @throws RequestException
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws UnsupportedLanguagesException
     */
    private function prepareSourceUrl(): bool
    {
        $is_root_path = parse_url($this->getDestination(), PHP_URL_PATH) == '/';

        $db_connection = $this->context->db->getConnection();

        $db_connection->beginTransaction();

        /**
         * If current language is from_language
         * Then translate current url for all languages
         * */
        if (
            $this->getLanguage() == $this->getFromLanguage()
            || $is_root_path
            || !$this->getTranslation()->url->isPathTranslation()
        ) {
            $this->setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->getAcceptLanguages())
                    ->url->translate(
                        [$this->getDestination()],
                        $verbose,
                        true,
                        true
                    )
                [$this->getDestination()] ?? null
            );

            /**
             * Set source origin URL
             * */
            $this->setSourceUrl($this->getDestination());
        } else {
            /**
             * Set source origin URL
             * */
            $this->setSourceUrl(
                $this->getSourceUrlFromTranslate(
                    $this->getDestination(),
                    $this->getLanguage()
                )
            );


            /**
             * Set current url all translations
             * */
            $this->setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->getAcceptLanguages())
                    ->url
                    ->translate(
                        [$this->getSourceUrl()],
                        $verbose,
                        true,
                        true
                    )[$this->getSourceUrl()]
                ?? null
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        Environment::server('REQUEST_URI', $this->getSourceUrl() ?? '/');

        /**
         * Handling 404 action page
         * Running page_not_found callable
         * */
        if ($this->getDestination() != null && $this->getSourceUrl() == null) {
            if ($this->restore_non_translated_urls === true) {
                $restored_url = $this->restoreNonTranslatedUrl(
                    $this->getDestination(),
                    $this->getLanguage()
                );

                if ($restored_url !== null) {
                    $this->redirect($restored_url);
                }
            }

            $db_connection->rollBack();


            if (
                isset($this->on_page_not_found)
                && is_callable($this->on_page_not_found)
            ) {
                call_user_func($this->on_page_not_found, $this);

                return false;
            } else {
                throw new  RequestException("404 Not Found", 404);
            }
        }

        try {
            $db_connection->commit();
        } catch (Exception $exception) {
            $db_connection->rollBack();
        }

        /**
         * Finally register shutdown function
         * that should translate url for all languages,
         * */
        register_shutdown_function(
            function () {
                if (!in_array(http_response_code(), [400, 401, 402, 403, 404])) {
                    $this->getTranslation()
                        ->setLanguages(
                            $this->getAcceptLanguages()
                        )
                        ->url->translate(
                            [$this->getSourceUrl()],
                            $verbose
                        );
                }
            }
        );

        return true;
    }

    /**
     * Redirect url
     *
     * @param string $url Url to redirect
     *
     * @return void
     */
    private function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Restore non translated urls
     *
     * @param string|null $url Url
     * @param string $language Language
     *
     * @return string|null
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws UnsupportedLanguagesException
     */
    private function restoreNonTranslatedUrl(
        ?string $url,
        string $language
    ): ?string {
        /**
         * Get translation from source
         * */
        $url = $this->getTranslation()->setLanguages([$language])->url
                ->translate(
                    [$url],
                    $verbose,
                    true,
                    true
                )[$url][$language] ?? null;

        if ($url == null) {
            return null;
        }

        return $url;
    }

    /**
     * Check exclusions array and expand
     * Callbacks and variables
     *
     * @return bool
     */
    private function isExclusion(): bool
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
     * @return bool
     *
     * @throws RequestException
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws UnsupportedLanguagesException
     */
    private function prepare(): bool
    {
        $this->setTranslation($this->context->translation);
        $this->setFromLanguage($this->context->localization->languages->getFromLanguage());

        return $this->prepareLanguage()
            && $this->prepareRegion()
            && $this->prepareCountry()
            && $this->prepareDestination()
            && $this->prepareSourceUrl()
            && $this->prepareReferer();
    }

    /**
     * Get Referer language
     *
     * @return string
     */
    public function getRefererLanguage(): string
    {
        return $this->referer_language;
    }

    /**
     * Set Referer Language
     *
     * @param string $_referer_language Referer language
     *
     * @return void
     */
    private function setRefererLanguage(string $_referer_language): void
    {
        $this->referer_language = $_referer_language;
    }

    /**
     * Prepare Referer language
     *
     * @return bool
     */
    private function prepareRefererLanguage(): bool
    {
        $http_referer = Environment::server('HTTP_REFERER');

        /**
         * Set origin referer
         * */
        Environment::server(
            'ORIG_HTTP_REFERER',
            $http_referer
        );

        /**
         * Taking language from current `$_SERVER['REQUEST_URI']`
         * */
        $language = $this->context->localization
            ->languages
            ->getLanguageFromUrl($http_referer);

        /**
         * If language does not exists in `$http_referer`
         * */
        if ($language == null) {
            $language = $this->context
                ->localization
                ->languages
                ->getDefaultLanguage(
                    Environment::server('HTTP_HOST')
                );
        }

        /*
         * Setting current instance language
         * */
        $this->setRefererLanguage($language);

        /*
         * Remove Language from URI
         * */
        $http_referer = $this->removeLanguageFromURI($http_referer);

        /**
         * Change HTTP_REFERER value
         * */
        Environment::server('HTTP_REFERER', $http_referer);

        return true;
    }

    /**
     * Prepare language
     *
     * @return bool
     */
    private function prepareLanguage(): bool
    {
        /**
         * Check if tried to access from cli
         * */
        $request_uri = Environment::server('REQUEST_URI');

        if ($request_uri !== null) {
            Environment::server('ORIG_REQUEST_URI', $request_uri);

            $this->setOrigRequestUri(
                URL::removeQueryVars(
                    $request_uri,
                    $this->context->prefix . '-' . $this->editor_query_key
                )
            );
        }

        $this->setDefaultLanguage(
            $this->context->localization->languages
                ->getDefaultLanguage(Environment::server('HTTP_HOST'))
        );

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->localization->languages
            ->getLanguageFromUrl($request_uri);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->getDefaultLanguage();
        }

        /**
         * Setting current instance language
         * */
        $this->setLanguage($language);

        $url = Environment::server('REQUEST_URI');

        /**
         * Remove Language from URI
         * */
        $new_url = $this->removeLanguageFromURI($url);

        /**
         * When user trying to access url that contains `domain`
         * that included in localization config
         * and that included also default language then redirect
         * to url without language code
         *
         * @example https://test.ru included in localization config and have default
         *          language `ru`, and user trying to open url https://test.ru/ru/
         *          then system redirects that url to https://test.ru
         *          or https://test.ru/ru/магазин redirect to https://test.ru/магазин
         *
         * @since 1.1.0
         * */
        if (
            rtrim($new_url, '/') != rtrim($url, '/')
            && $this->getDefaultLanguage() == $this->getLanguage()
        ) {
            $this->redirect($new_url);
        }

        /**
         * Setting REQUEST_URI
         * */
        Environment::server('REQUEST_URI', $new_url);

        return true;
    }

    /**
     * Get current country
     *
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Set current country
     *
     * @param mixed $country Country name
     *
     * @return void
     */
    private function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * Set current region
     *
     * @param string|null $region Region name
     *
     * @return void
     */
    private function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    /**
     * Get current region
     *
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Prepare country
     *
     * @return bool
     */
    private function prepareCountry(): bool
    {
        $country = $this->context->localization->countries
            ->getConfig(Environment::server('HTTP_HOST'), 'name');

        $this->setCountry($country);

        $this->getTranslation()->setCountry($this->getCountry());

        return true;
    }

    /**
     * Prepare region
     *
     * @return bool
     */
    private function prepareRegion(): bool
    {
        $region = $this->context->localization->regions
            ->getConfig(Environment::server('HTTP_HOST'), 'name');

        $this->setRegion($region);

        $this->getTranslation()->setRegion($this->getRegion());

        return true;
    }

    /**
     * Remove language from uri string
     *
     * @param string $uri Referenced variable of URI string
     *
     * @return string
     */
    private function removeLanguageFromURI(?string $uri): string
    {
        $parts = parse_url(trim($uri, '/'));
        if (isset($parts['path'])) {
            $path = explode('/', ltrim($parts['path'], '/'));

            if ($this->context->localization->languages->validateLanguage($path[0])) {
                unset($path[0]);
            }
            $parts['path'] = implode('/', $path);
            $new_url = URL::buildUrl($parts);
            $uri = empty($new_url) ? '/' : $new_url;
        }

        return $uri;
    }

    public $source_type_map = [];

    /**
     * Get type of response
     *
     * @param $source  null|string Source
     * @param $content null|string Content
     *
     * @return string|null
     */
    private function getType(?string $source, ?string $content): ?string
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
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws UnsupportedLanguagesException
     */
    private function translateBuffer(?string $content): ?string
    {
        $status = http_response_code();

        /*
         * If response status is success
         * */
        if (in_array($status, range(200, 299))) {
            $type = $this->getType($this->getSourceUrl(), $content);

            if ($type !== null) {
                /**
                 * Define type of translator
                 *
                 * @var Translator $translator
                 */
                $translator = $this
                    ->getTranslation()
                    ->setLanguages([$this->getLanguage()])
                    ->{$type};

                if ($this->isEditor()) {
                    /**
                     * Define translator type
                     *
                     * @var HTML $translator
                     */
                    $translator->setHelperAttributes(true);
                }

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
                                $this->addMainJavaScriptNode($dom, $head);
                                $this->addXHRManipulationJavaScript($dom, $head);
                                $this->addAlternateLinkNodes($dom, $head);
                                if ($this->allow_editor) {
                                    $this->addEditorAssets($dom, $head);
                                }
                            }
                        }
                    );
                }

                /**
                 * Translate content
                 *
                 * @var Translator $translator
                 * */
                $content = $translator
                        ->translate(
                            [$content],
                            $verbose,
                            false,
                            $this->isEditor()
                        )[$content][$this->getLanguage()]
                    ?? $content;
            }
        }

        $this->verbose['end'] = microtime(true);
        $this->verbose['duration'] = $this->verbose['start']
            - $this->verbose['end'];

        return $content;
    }

    /**
     * Save Editor if request is POST and has parameter %prefix%-form
     *
     * @return bool
     */
    private function editorSave(): bool
    {
        if (
            $this->isEditor()
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
                $verbose
            );

            if (is_callable($this->editor_after_save_callback)) {
                call_user_func_array(
                    $this->editor_after_save_callback,
                    [&$verbose, $this]
                );
            }

            header('Content-Type: application/json');
            echo json_encode($verbose, JSON_PRETTY_PRINT);

            return true;
        }

        return false;
    }


    private $verbose = [];

    /**
     * Start request translation
     *
     * @return void
     * @throws RequestException
     * @throws ConnectionException
     * @throws InvalidArgumentException
     * @throws UnsupportedLanguagesException
     */
    public function start(): void
    {
        $this->verbose['start'] = microtime(true);

        if (
            $this->isCli()
            || $this->isExclusion()
            || !in_array(
                Environment::server('REQUEST_METHOD'),
                $this->getAcceptRequestMethods()
            )
        ) {
            return;
        }

        if (!$this->prepare()) {
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

                    $this->editor_url_translations[$language] = URL::addQueryVars(
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

            if (
                isset($_GET[$this->context->prefix . '-' . $this->editor_query_key])
                && ($this->getLanguage() != $this->getFromLanguage())
            ) {
                $this->is_editor = true;
            }

            if ($this->editorSave()) {
                die;
            }
        }

        $this->setReady(true);

        /**
         * Prevent pages with main(from_language) and default language translation
         * */
        if (
            ($this->getFromLanguage() !== $this->getLanguage())
            || ($this->default_http_host != Environment::server('HTTP_HOST'))
        ) {
            ob_start([$this, 'translateBuffer']);
        }
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
    private function addAlternateLinkNodes(DOMDocument $dom, DOMNode $parent): void
    {
        if ($this->getUrlTranslations() !== null) {
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
     * Get Accepted languages
     *
     * @param bool $assoc Return all related info for language
     *
     * @return array
     */
    public function getAcceptLanguages(bool $assoc = false)
    {
        return $this->context->localization->languages
            ->getAcceptLanguages(
                $assoc,
                Environment::server('HTTP_HOST')
            );
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
    private function addMainJavaScriptNode(
        DOMDocument &$dom,
        DOMNode $parent
    ): void {
        $config = json_encode(
            [
                'i18n' => [
                    'current_language' => $this->getLanguage(),
                    'default_language' => $this->getDefaultLanguage(),
                    'accept_languages' => $this->getAcceptLanguages(true),
                    'language_query_key' => $this->context->localization->languages
                        ->getLanguageQueryKey(),
                    'editor' => [
                        'is_editor' => $this->isEditor(),
                        'query_key' => $this->editor_query_key,
                        'url_translations' => $this->getEditorUrlTranslations()
                    ],
                    'prefix' => $this->context->prefix,
                    'orig_request_uri' => $this->getOrigRequestUri(),
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
    private function addEditorAssets(DOMDocument $dom, DOMNode $parent): void
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
    private function addXHRManipulationJavaScript(
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
        return $this->destination;
    }

    /**
     * Set Request Destination
     *
     * @param string $destination Destination uri
     *
     * @return void
     */
    private function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    /**
     * Get Source Url
     *
     * @return string
     */
    public function getSourceUrl(): ?string
    {
        return $this->source_url;
    }

    /**
     * Set Source Url
     *
     * @param string $source_url Source Url
     *
     * @return void
     */
    private function setSourceUrl(?string $source_url): void
    {
        $this->source_url = $source_url;
    }

    /**
     * Get Url translations list
     *
     * @return array
     */
    public function getUrlTranslations(): ?array
    {
        return $this->url_translations;
    }

    /**
     * Set Url Translations list
     *
     * @param array $url_translations Url Translations list
     *
     * @return void
     */
    private function setUrlTranslations(?array $url_translations): void
    {
        $this->url_translations = $url_translations;
    }

    /**
     * Get Request current Language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set Request current language
     *
     * @param string $language Language
     *
     * @return void
     */
    private function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * Get Translation Component
     *
     * @return Translation
     */
    public function getTranslation(): Translation
    {
        return $this->translation;
    }

    /**
     * Set Translation component
     *
     * @param Translation $translation Translation component
     *
     * @return void
     */
    private function setTranslation(Translation $translation): void
    {
        $this->translation = $translation;
    }

    /**
     * Get main content language
     *
     * @return string
     */
    public function getFromLanguage(): string
    {
        return $this->from_language;
    }

    /**
     * Set main content language
     *
     * @param string $from_language Language code
     *
     * @return void
     */
    private function setFromLanguage(string $from_language): void
    {
        $this->from_language = $from_language;
    }

    /**
     * If is editor mode
     *
     * @return bool
     */
    public function isEditor(): bool
    {
        return $this->is_editor;
    }

    /**
     * Get editor urls on all allowed languages
     *
     * @return array
     */
    public function getEditorUrlTranslations(): array
    {
        return $this->editor_url_translations;
    }

    /**
     * If request component ready to use
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return $this->ready;
    }

    /**
     * Set ready status
     *
     * @param bool $ready Ready status
     *
     * @return void
     */
    private function setReady(bool $ready): void
    {
        $this->ready = $ready;
    }

    /**
     * Get default language for current hostname
     * Its taking from localization_config
     *
     * @return string
     */
    public function getDefaultLanguage(): string
    {
        return $this->default_language;
    }

    /**
     * Set default language
     *
     * @param string $default_language Default language
     *
     * @return void
     */
    private function setDefaultLanguage(string $default_language): void
    {
        $this->default_language = $default_language;
    }
}
