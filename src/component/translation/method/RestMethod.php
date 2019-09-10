<?php


namespace NovemBit\i18n\component\translation\method;

/*
 * Dummy method of translation
 * That returns {lang}-{text} as translation
 * */

use Exception;
use NovemBit\i18n\system\helpers\URL;

class RestMethod extends Method
{
    public $ssl = false;

    public $remote_host;

    public $remote_path = 'i18n/rest/v1';

    public $api_key;
    /**
     * @param array $texts
     *
     * @return array
     * @throws Exception
     */
    protected function doTranslate(array $texts)
    {


        /* API URL */
        $url = URL::buildUrl([
            'schema'=>$this->ssl ? "https" : "http",
            'host'=>$this->remote_host,
            'path'=>$this->remote_path,
            'query'=>'api_key='.$this->api_key
        ]);

        var_dump($url);die;

        /* Init cURL resource */
        $ch = curl_init($url);

        /* Array Parameter Data */
        $data = ['languages'=>$this->context->getLanguages(), 'email'=>'itsolutionstuff@gmail.com'];

        /* pass encoded JSON string to the POST fields */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* execute request */
        $result = curl_exec($ch);

        /* close cURL resource */
        curl_close($ch);

        return $result;

    }

}