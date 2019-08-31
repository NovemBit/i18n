<?php

namespace NovemBit\i18n\component;


use Exception;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\URL;

/**
 * @property Module $context
 */
class Request extends Component
{

    public $language;
    public $dest;
    public $from_language;
    public $accepted_languages;
    public $source_url;
    public $url_translations;

    /**
     * @throws Exception
     */
    function init()
    {

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

        $re_translate = $this->context->translation->setLanguages($to_language)->url->reTranslate([$translate]);
        if (isset($re_translate[$translate])) {
            return $re_translate[$translate];
        }

        return null;
    }

    /**
     * @return string
     */
    private function getCurrentLanguage()
    {
        return $this->context->languages->getCurrentLanguage();
    }

    /**
     * @return string
     */
    private function getDefaultLanguage()
    {
        return $this->context->languages->from_language;
    }

    /**
     * @return array|null
     */
    private function getAcceptLanguage()
    {
        return $this->context->languages->getAcceptLanguages();
    }

    /**
     * @return mixed
     */
    private function getUrlDest()
    {
        $dest = trim($_SERVER['REQUEST_URI'], '/');
        $dest = URL::removeQueryVars($dest, $this->context->languages->language_query_key);

        return $dest;
    }

    private function prepare(){

        $this->language           = $this->getCurrentLanguage();
        $this->from_language      = $this->getDefaultLanguage();
        $this->accepted_languages = $this->getAcceptLanguage();
        $this->dest               = $this->getUrlDest();
    }

    /**
     * Start request
     *
     * @throws Exception
     */
    public function start()
    {
        $this->prepare();

        /*var_dump([
            'url_dest'         => $this->getUrlDest(),
            'default_language' => $this->getDefaultLanguage(),
            'current_language' => $this->getCurrentLanguage(),
            'accept_languages' => $this->getAcceptLanguage()
        ]);*/

        /*
         * If current language is default language
         * Then translate current url for all languages
         * */
        if ($this->language == $this->from_language) {
            $this->url_translations
                = $this->context->translation
                ->setLanguages($this->accepted_languages)
                ->url->translate([$this->dest]);

            return;
        }

        $this->source_url = $this->getSourceUrlFromTranslate($this->dest, $this->language);


        if ($this->dest != null && $this->source_url == null) {
            throw new \Exception("404 Not Found", 404);
        }

        /*var_dump([
            'source_url'         => $this->source_url
        ]);*/
        /*
         * Manipulating REQUEST_URI
         * */
        $_SERVER['REQUEST_URI'] = '/' . $this->source_url;

        ob_start();

        register_shutdown_function(function () {

            $status = http_response_code();

            if ($status < 200 || $status >= 300) {
                return;
            }

            $content = ob_get_contents();

            ob_end_clean();

            $content
                = $this->context->translation
                ->setLanguages([$this->language])
                ->html
                ->translate([$content])[$content][$this->language];

            echo $content;
        });

    }

}