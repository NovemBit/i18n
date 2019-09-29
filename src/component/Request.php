<?php
/**
 * Request component
 * php version 7.2.10
 *
 * @category Component
 * @package  Composer
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */
namespace NovemBit\i18n\component;

use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\URL;
use NovemBit\i18n\system\helpers\DataType;
use NovemBit\i18n\system\exception\Exception;

/**
 * @property Module $context
 */
class Request extends Component
{
    /*
     * Translation component
     * */
    private $_translation;

    /*
     * Languages of URL
     * */
    private $_language;
    private $_referer_language;

    /**
     * Originals
     * Destination is @REQUEST_URI
     * Referer is @HTTP_REFERER
     * */
    private $_destination;
    private $_referer;

    /**
     * Source urls
     * */
    private $_source_url;
    private $_referer_source_url;

    private $_url_translations;
    private $_referer_translations;

    public $editor_query_key = "editor";

    public $editor;

    /**
     * @return mixed
     */
    public function getRefererSourceUrl()
    {
        return $this->_referer_source_url;
    }

    /**
     * @param mixed $_referer_source_url
     */
    public function setRefererSourceUrl($_referer_source_url)
    {
        $this->_referer_source_url = $_referer_source_url;
    }

    /**
     * @return mixed
     */
    public function getRefererTranslations()
    {
        return $this->_referer_translations;
    }

    /**
     * @param mixed $_referer_translations
     */
    public function setRefererTranslations($_referer_translations)
    {
        $this->_referer_translations = $_referer_translations;
    }

    /**
     * @param $translate
     * @param $to_language
     *
     * @return null
     * @throws Exception
     */
    private function getSourceUrlFromTranslate($translate, $to_language)
    {

        $re_translate = $this->context->translation
            ->setLanguages($to_language)->url->reTranslate([$translate]);
        if (isset($re_translate[$translate])) {
            return $re_translate[$translate];
        }

        return null;
    }

    /**
     * @return bool
     */
    private function prepareDestination()
    {
        $dest = '/' . trim($_SERVER['REQUEST_URI'], '/');
        $dest = URL::removeQueryVars(
            $dest,
            $this->context->languages->language_query_key
        );
        $dest = urldecode($dest);
        $this->_setDestination($dest);
        return true;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->_referer;
    }

    /**
     * @param mixed $_referer
     */
    public function setReferer($_referer)
    {
        $this->_referer = $_referer;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function _prepareReferer()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->_prepareRefererLanguage();

            $referer = trim($_SERVER['HTTP_REFERER'], '/');
            $referer = URL::removeQueryVars(
                $referer,
                $this->context->languages->language_query_key
            );
            $referer = urldecode($referer);
            $this->setReferer($referer);

            $this->_prepareRefererSourceUrl();
        }

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function _prepareRefererSourceUrl()
    {
        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->getLanguage() == $this->context->languages->getFromLanguage()) {
            $this->setRefererTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getReferer()])[$this->getReferer()]
            );

            $this->setRefererSourceUrl($this->getReferer());
        } else {
            /*
            * Set source origin URL
            * */
            $this->setRefererSourceUrl(
                $this->getSourceUrlFromTranslate(
                    $this->getReferer(),
                    $this->getRefererLanguage()
                )
            );

            /*
             * Set current url all translations
             * */
            $this->setRefererTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getSourceUrl()])[$this->getSourceUrl()]
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['HTTP_REFERER'] = $this->getRefererSourceUrl();

        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function _prepareSourceUrl()
    {
        /*
         * If current language is from_language
         * Then translate current url for all languages
         * */

        if ($this->getLanguage() == $this->context->languages->getFromLanguage()
            || $this->getDestination() == '/'
        ) {
            $this->setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getDestination()])
                [$this->getDestination()]
            );

            /*
             * Set source origin URL
             * */
            $this->setSourceUrl($this->getDestination());
        } else {
            /*
            * Set source origin URL
            * */
            $this->setSourceUrl(
                $this->getSourceUrlFromTranslate(
                    $this->getDestination(),
                    $this->getLanguage()
                )
            );

            /*
             * Set current url all translations
             * */
            $this->setUrlTranslations(
                $this->getTranslation()
                    ->setLanguages($this->context->languages->getAcceptLanguages())
                    ->url->translate([$this->getSourceUrl()])[$this->getSourceUrl()]
            );
        }

        /**
         * Setting source url as @REQUEST_URI
         * */
        $_SERVER['REQUEST_URI'] = $this->getSourceUrl();

        if ($this->getDestination() != null && $this->getSourceUrl() == null) {
            throw new Exception("404 Not Found", 404);
        }

        return true;
    }

    /**
     * @throws Exception
     * @throws Exception
     */
    private function _prepare()
    {

        $this->_setTranslation($this->context->translation);

        if (isset($_GET[$this->context->prefix . '-' . $this->editor_query_key])) {
            $this->editor = true;
        }

        return $this->_prepareLanguage()
            && $this->prepareDestination()
            && $this->_prepareSourceUrl()
            && $this->_prepareReferer();
    }

    /**
     * @return mixed
     */
    public function getRefererLanguage()
    {
        return $this->_referer_language;
    }

    /**
     * @param mixed $_referer_language
     */
    public function setRefererLanguage($_referer_language)
    {
        $this->_referer_language = $_referer_language;
    }

    /**
     * @throws Exception
     */
    private function _prepareRefererLanguage()
    {

        $_SERVER["ORIG_HTTP_REFERER"] = $_SERVER["HTTP_REFERER"];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages
            ->getLanguageFromUrl($_SERVER["HTTP_REFERER"]);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->context->languages->getDefaultLanguage();
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
     * @throws Exception
     * @throws Exception
     */
    private function _prepareLanguage()
    {
        /*
         * Check if tried to access from cli
         * */
        if (!isset($_SERVER['REQUEST_URI'])) {
            throw new Exception('Access without http request was denied.');
        }


        $_SERVER["ORIG_REQUEST_URI"] = $_SERVER["REQUEST_URI"];

        /**
         * Taking language from current @REQUEST_URI
         * */
        $language = $this->context->languages
            ->getLanguageFromUrl($_SERVER['REQUEST_URI']);

        /**
         * If language does not exists in @URL
         * */
        if ($language == null) {
            $language = $this->context->languages->getDefaultLanguage();
        }

        /*
         * Setting current instance language
         * */
        $this->setLanguage($language);

        /*
         * Remove Language from URI
         * */
        $this->_removeLanguageFromURI($_SERVER['REQUEST_URI']);

        return true;
    }

    /**
     * @param $uri
     */
    private function _removeLanguageFromURI(&$uri)
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

    /**
     * @param  $content
     * @return string|string[]|null
     * @throws Exception
     */
    public function translateBuffer($content)
    {
        $status = http_response_code();

        /*
         * If response status is success
         * */
        if (in_array($status, range(200, 299))) {

            $type = DataType::getType($content);

            if ($type !== 0) {

                /*
                 * Translate content
                 * */
                $content = $this
                    ->getTranslation()->setLanguages($this->getLanguage())
                    ->{$type}
                    ->translate([$content])[$content][$this->getLanguage()];

                if ($type == "html") {
                    $content = preg_replace(
                        '/(<head.*?>)/is',
                        '$1' . PHP_EOL . $this->getHeadAdditionalTags(),
                        $content,
                        1
                    );
                }

            }
        }

        return $content;
    }

    /**
     * Start request
     *
     * @throws Exception
     */
    public function start()
    {
        if (!$this->_prepare()) {
            return;
        }

        /*var_dump([
            'url_dest' => $this->getDestination(),
            'from_language' => $this->context->languages->from_language,
            'language' => $this->getLanguage(),
            'source_url'=>$this->getSourceUrl(),
            'orig_request_uri'=>$_SERVER["ORIG_REQUEST_URI"]
        ]);*/

        ob_start([$this, 'translateBuffer']);
    }

    /**
     * @return string
     */
    private function getHeadAdditionalTags()
    {
        $tags = '';

        $tags .= $this->_getMainJavaScriptTag();

        $tags .= $this->_getXHRManipulationJavaScriptTag();

        if ($this->editor) {
            $tags .= $this->_getEditorJavaScriptTag();
        }

        $tags .= $this->getAlternateLinkTags();
        return $tags;
    }

    private function getAlternateLinkTags()
    {
        $tags = '';
        foreach ($this->getUrlTranslations() as $language => $translate) {
            $tags .= sprintf(
                "<link rel=\"alternate\" hreflang=\"%d\" href=\"%s\">",
                $language,
                $translate
            );
        }
        return $tags;
    }

    /**
     * @return string
     */
    private function _getMainJavaScriptTag()
    {
        $config = json_encode(
            [
                'i18n' => [
                    'current_language' => $this->getLanguage(),
                    'accept_languages' => $this->context->languages
                        ->getAcceptLanguages(),
                    'language_query_key' => $this->context->languages
                        ->language_query_key,
                    'editor_query_key' => $this->editor_query_key,
                    'prefix' => $this->context->prefix,
                    'orig_uri' => $this->getDestination(),
                    'uri' => $this->getSourceUrl(),
                    'orig_referer' => $this->getReferer(),
                    'referer' => $this->getRefererSourceUrl(),
                    'url_translations' => $this->getUrlTranslations(),
                    'referer_translations' => $this->getRefererTranslations(),

                ]
            ]
        );
        $script = "(function() {window.novembit={$config}})()";
        return sprintf(
            "<script type=\"application/javascript\">%s</script>",
            $script
        );
    }

    /**
     * @return string
     */
    private function _getEditorJavaScriptTag()
    {
        $script = file_get_contents(__DIR__ . '/request/assets/js/editor.js');
        $css = file_get_contents(__DIR__ . '/request/assets/css/editor.css');

        return implode(
            '', [
                sprintf(
                    "<script type=\"application/javascript\">%s</script>",
                    $script
                ),
                '<style type="text/css">' . $css . '</style>'
            ]
        );
    }

    /**
     * @return string
     */
    private function _getXHRManipulationJavaScriptTag()
    {
        $script = file_get_contents(__DIR__ . '/request/assets/js/xhr.js.js');
        return sprintf(
            "<script type=\"application/javascript\">%s</script>",
            $script
        );
    }

    /**
     * @return mixed
     */
    public function getDestination()
    {
        return $this->_destination;
    }

    /**
     * @param mixed $destination
     */
    private function _setDestination($destination)
    {
        $this->_destination = $destination;
    }

    /**
     * @return mixed
     */
    public function getSourceUrl()
    {
        return $this->_source_url;
    }

    /**
     * @param mixed $source_url
     */
    private function setSourceUrl($source_url)
    {
        $this->_source_url = $source_url;
    }

    /**
     * @return mixed
     */
    public function getUrlTranslations()
    {
        return $this->_url_translations;
    }

    /**
     * @param mixed $url_translations
     */
    private function setUrlTranslations($url_translations)
    {
        $this->_url_translations = $url_translations;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->_language;
    }

    /**
     * @param mixed $language
     */
    private function setLanguage($language)
    {
        $this->_language = $language;
    }

    /**
     * @return Translation
     */
    public function getTranslation()
    {
        return $this->_translation;
    }

    /**
     * @param Translation $translation
     */
    private function _setTranslation(Translation $translation)
    {
        $this->_translation = $translation;
    }
}