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
use NovemBit\i18n\Module;
use NovemBit\i18n\system\Component;
use NovemBit\i18n\system\helpers\Environment;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @var string
     */
    public $welcome = 'NovemBit I18n v1.3';

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
    private $api_key;

    /**
     * Start rest request
     *
     * @return void
     */
    public function start(): void
    {

        $endpoint = parse_url(Environment::server('REQUEST_URI'), PHP_URL_PATH);

        $endpoint = trim($endpoint, '/');
        $re = '/^' . preg_quote($this->endpoint, '/') . "(.*)$/";

        if (preg_match($re, $endpoint, $matches)) {
            $action = trim($matches[1], '/');

            $actionMethodParts = array_filter(explode('-', $action));

            $actionMethod = $this->actionPrefix;

            $this->validateAPI();

            if (!isset($this->api_key)) {
                $actionMethod .= $this->restrictAction;
            } elseif (empty($actionMethodParts)) {
                $actionMethod .= $this->defaultAction;
            } else {
                foreach ($actionMethodParts as $key => $actionMethodPart) {
                    $actionMethod .= ucfirst($actionMethodPart);
                }
            }

            if ($actionMethod != '' && method_exists($this, $actionMethod)) {
                header('Content-Type: application/json');
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
    private function validateAPI(): void
    {
        if (isset($_GET['api_key']) && in_array($_GET['api_key'], $this->api_keys, true)) {
            $this->api_key = $_GET['api_key'];
        }
    }

    /**
     * Translate Action method
     *
     * @return array|int
     */
    public function actionTranslate()
    {

        $result = [
            'status' => interfaces\Rest::STATUS_NONE,
            'message' => 'Invalid parameters.'
        ];

        if (isset($_POST['texts'], $_POST['languages'])) {

            /**
             * Setting language component configuration
             * */
            $localization_config = $_POST['localization_config'] ?? null;

            $this->context->localization->reInit($localization_config);

            try {
                $translate = $this->context
                    ->translation
                    ->setLanguages($_POST['languages'])
                    ->method
                    ->translate($_POST['texts']);

                $result = [
                    'status' => empty($translate)
                        ? interfaces\Rest::STATUS_EMPTY
                        : interfaces\Rest::STATUS_DONE,
                    'message' => 'Translation done.',
                    'translation' => $translate
                ];
            } catch (Exception $e) {
                $result = [
                    'status' => interfaces\Rest::STATUS_ERROR,
                    'message' => $e->getMessage()
                ];
            } catch (InvalidArgumentException $e) {
                $result = [
                    'status' => interfaces\Rest::STATUS_ERROR,
                    'message' => $e->getMessage()
                ];
            }
        }

        $result['welcome'] = $this->welcome;
        
        return $result;
    }

    /**
     * Index Action method
     *
     * @return array
     */
    public function actionIndex(): array
    {
        return ['api_key' => $this->api_key];
    }

    /**
     * Restrict Action method
     *
     * @return array
     */
    public function actionRestrict(): array
    {
        http_response_code(403);
        return ['messages' => 'You don\'t have access to this endpoint.'];
    }
}
