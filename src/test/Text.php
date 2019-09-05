<?php


namespace NovemBit\i18n\test;


use Exception;
use NovemBit\i18n\system\Component;

/**
 * @property  Test context
 */
class Text extends Component
{

    private $nodes
        = [
            ' To load an extension, you need to have it available as a.',
            'Me & you ',
            '123'
        ];

    /**
     * @param       $log
     * @param array $class
     * @param       $return
     *
     * @return mixed|string|null
     */
    public function log($log, $class = [], $return = false)
    {
        return $this->context->log($log, $class, $return);
    }

    /**
     * @return string
     * @throws Exception
     */
    private function test()
    {
        $log         = '';
        $translation = $this->context->context->translation;

        $result = $translation->setLanguages(['fr','hy'])->text->translate(
            $this->nodes
        );

        $log.=var_export($result,true);

        /*foreach ($result as $source => $value) {
            $log .= $this->log(implode('',
                [
                    $source,
                    $this->log(
                        htmlentities($value['fr'], ENT_COMPAT, 'UTF-8'),
                        ['code'],
                        true
                    )
                ]
            ), ['code'], true);
        }*/

        return $log;
    }

    /**
     * @throws Exception
     */
    public function start()
    {

        $this->log(self::class . ' Testing' . $this->test(), ['code']);


        parent::init(); // TODO: Change the autogenerated stub

    }
}