<?php
/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\rest;


use Exception;
use NovemBit\i18n\component\translation\method\Method;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\i18n\component\translation\type\Text;
use NovemBit\i18n\component\translation\type\URL;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;

/**
 * Rest component
 *
 * @category Component
 * @package  Component
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 *
 * @property Module $context
 */
class Rest extends Component implements interfaces\Rest
{

    /**
     * Available types of rest method
     *
     * @var string[]
     * */
    public $available_types = [
        Method::NAME,
        Text::NAME,
        URL::NAME,
        HTML::NAME,
        JSON::NAME
    ];

    /**
     * Api keys list
     *
     * @var string[]
     * */
    public $api_keys = [];

    /**
     * Default endpoint of rest api
     *
     * @var string
     * */
    public $endpoint = "i18n/rest/v1";

    /**
     * Action prefix
     *
     * @var string
     * */
    public $actionPrefix = 'action';

    /**
     * Default action
     *
     * @var string
     * */
    public $defaultAction = 'index';

    /**
     * Restring action
     *
     * @var string
     * */
    public $restrictAction = 'restrict';


    /**
     * Current api key
     *
     * @var string
     * */
    private $_api_key;

    /**
     * Start rest request
     *
     * @return void
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

            $this->_validateAPI();

            if (!isset($this->_api_key)) {
                $actionMethod .= $this->restrictAction;
            } elseif (empty($actionMethodParts)) {
                $actionMethod .= $this->defaultAction;
            } else {
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

    /**
     * Validation of api key
     *
     * @return void
     */
    private function _validateAPI()
    {
        if (isset($_GET['api_key']) && in_array($_GET['api_key'], $this->api_keys)) {
            $this->_api_key = $_GET['api_key'];
        }
    }

    /**
     * Translate Action method
     *
     * @return array|int
     */
    public function actionTranslate()
    {

        $result = [];
        if (isset($_POST['from_language'])
            && isset($_POST['texts'])
            && isset($_POST['languages'])
            && isset($_POST['type'])
        ) {

            if (!in_array($_POST['type'], $this->available_types)) {
                $result['status']=0;
                return $result;
            }
            /**
             * Set from language
             * */
            $this->context->languages->from_language = $_POST['from_language'];

            try {
                $result=[
                    'status'=>1,
                    'translation'=>$this->context->translation
                        ->setLanguages($_POST['languages'])
                        ->{$_POST['type']}
                        ->translate($_POST['texts'])
                ];
            } catch (\NovemBit\i18n\system\exception\Exception $exception){
                $result = [
                    'status'=>-1,
                    'message'=>'Unexpected Error.'
                ];
            }
            return $result;
        }

        return 0;
    }

    /**
     * Index Action method
     *
     * @return array
     */
    public function actionIndex()
    {
        return ['api_key' => $this->_api_key];
    }

    /**
     * Restrict Action method
     *
     * @return array
     */
    public function actionRestrict()
    {
        http_response_code(403);
        return ['messages' => 'You don\'t have access to this endpoint.'];
    }

}