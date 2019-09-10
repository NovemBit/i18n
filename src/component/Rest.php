<?php

namespace NovemBit\i18n\component;


use ErrorException;
use Exception;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
/**
 * @property Module context
 * */
class Rest extends Component
{

    public $api_keys = [];

    public $endpoint = "i18n/rest/v1";

    public $actionPrefix = 'action';

    public $defaultAction = 'index';
    public $restrictAction = 'restrict';


    private $api_key;
    /**
     *
     */
    public function start()
    {
        $endpoint = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $endpoint = trim($endpoint, '/');
        $re = '/^' . preg_quote($this->endpoint, '/') . "(.*)$/";

        if (preg_match($re, $endpoint, $matches)) {

            $action = trim($matches[1], '/');

            $actionMethodParts = array_filter(explode('-', $action));

            $actionMethod = $this->actionPrefix;

            $this->validateAPI();

            if (!isset($this->api_key)) {
                $actionMethod .= $this->restrictAction;
            }elseif (empty($actionMethodParts))
                $actionMethod .= $this->defaultAction;
            else {
                foreach ($actionMethodParts as $key => $actionMethodPart) {
                    $actionMethod .= ucfirst($actionMethodPart);
                }
            }

            if ($actionMethod != '' && method_exists($this, $actionMethod)) {
                echo json_encode($this->{$actionMethod}());
                die;
            }
        }

    }

    private function validateAPI(){
        if(isset($_GET['api_key']) && in_array($_GET['api_key'],$this->api_keys)){
            $this->api_key = $_GET['api_key'];
        }
    }

    /**
     * @return array|int
     * @throws Exception
     */
    public function actionTranslate()
    {

        if(isset($_POST['texts']) && isset($_POST['languages'])){

            $translation = $this->context->translation->setLanguages($_POST['languages'])->method->translate($_POST['texts']);
            return $translation;
        }

        return 0;
    }

    public function actionIndex()
    {
        return ['api_key'=>$this->api_key];
    }

    public function actionRestrict()
    {
        http_response_code(403);
        return ['messages'=>'You don\'t have access to this endpoint.'];
    }

}