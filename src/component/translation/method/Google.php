<?php

/**
 * Translation component
 * php version 7.2.10
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @version  GIT: @1.0.1@
 * @link     https://github.com/NovemBit/i18n
 */

namespace NovemBit\i18n\component\translation\method;

use Exception;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Translate\V2\TranslateClient;
use NovemBit\i18n\component\translation\method\exceptions\MethodException;
use NovemBit\i18n\component\translation\Translation;

/**
 * Google Translate method of translation
 *
 * @category Component\Translation\Method
 * @package  Component\Translation\Method
 * @author   Aaron Yordanyan <aaron.yor@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://github.com/NovemBit/i18n
 */
class Google extends Method
{
    /**
     * Google console api key for GT
     *
     * @var string
     * */
    public string $api_key;

    public int $api_limit_expire_delay = 3600;

    /**
     * {@inheritdoc}
     * */
    public string $exclusion_pattern = '<span translate="no">$0</span>';

    /**
     * {@inheritdoc}
     *
     * @return void
     * @throws MethodException
     */
    public function mainInit(): void
    {
        if (!isset($this->api_key)) {
            throw new MethodException('Missing Google Cloud Translate API key.');
        }
    }

    /**
     * Doing translate method
     *
     * @param array $nodes Array of texts to translate
     * @param string $from_language
     * @param array $to_languages
     * @param bool $ignore_cache
     *
     * @return array
     * @throws Exception
     */
    protected function doTranslate(
        array $nodes,
        string $from_language,
        array $to_languages,
        bool $ignore_cache
    ): array {
        $result = [];

        $timestamp = $this->getMutex();

        foreach ($to_languages as $language) {
            if ($from_language === $language) {
                foreach ($nodes as $text) {
                    $result[$text][$language] = $text;
                }
                continue;
            }

            if ($timestamp === null || $timestamp + $this->api_limit_expire_delay < time()) {
                $this->translateOneLanguage($nodes, $from_language, $language, $result);
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    private function getMutexPath(): string
    {
        return sys_get_temp_dir() . '/i18n-' . md5(self::class) . '_mutex';
    }

    /**
     * @return int|null
     */
    private function getMutex(): ?int
    {
        if (!file_exists($this->getMutexPath())) {
            return null;
        }
        return (int)file_get_contents($this->getMutexPath());
    }

    /**
     * @return void
     */
    private function setMutex(): void
    {
        file_put_contents($this->getMutexPath(), time());
    }

    /**
     * Translate texts to only one language
     *
     * @param array $texts Array of translatable texts
     * @param string $from_language Language code
     * @param string $to_language Language code
     * @param array $result Referenced variable of results
     *
     * @return void
     * @throws Exception
     */
    private function translateOneLanguage(
        array $texts,
        string $from_language,
        string $to_language,
        array &$result
    ): void {
        $request_data = array(
            'key' => $this->api_key,
            'source' => $from_language,
            'target' => $to_language
        );

        $gt_client = new  TranslateClient($request_data);
        $translations = [];

        try {
            // todo: count the character
            $chunks = array_chunk($texts, 100);

            foreach ($chunks as $chunk) {
                array_push($translations, ...$gt_client->translateBatch($chunk));
            }
        } catch (GoogleException $e) {
            $message = json_decode($e->getMessage(), true) ?? [];

            $this->setMutex();

            $this->getLogger()->warning(
                sprintf(
                    '%s: %s | Lang: %s | Texts: [%s]',
                    $message['error']['code'] ?? '000',
                    $message['error']['message'] ?? 'Google Translate: Undefined.',
                    $to_language,
                    implode(' | ', $texts)
                )
            );
        }

        foreach ($translations as $key => $item) {
            $node = str_replace(
                array('<span translate="no">', '</span>'),
                '',
                $item['text']
            );

            if (!empty($node)) {
                $result[$item['input']][$to_language] = $node;
            }
        }
    }
}
