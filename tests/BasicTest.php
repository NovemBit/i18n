<?php

namespace NovemBit\tests\i18n;

use NovemBit\i18n\Module;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicTest
 */
class BasicTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param $url
     * @param $language
     * @param $final
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testAdd($url, $language, $final): void
    {
        $this->assertEquals(
            $final,
            Module::instance()
                ->translation
                ->setLanguages([$language])
                ->url
                ->translate([$url])[$url][$language]
        );
    }

    public function setUp(): void
    {
        
        Module::instance(
            include(__DIR__ . '/../config/wp.php')
        );
    }

    public function additionProvider(): array
    {
        return [
            ['/','fr','/fr'],
            ['/shop', 'fr', '/fr/shop-fr'],
            ['/shop?test=123', 'fr', '/fr/shop-fr?test=123'],
            ['/shop?test=123&test2=321', 'fr', '/fr/shop-fr?test=123&test2=321'],
            ['/shop#test', 'fr', '/fr/shop-fr#test'],
            ['/shop/food', 'fr', '/fr/shop-fr/food-fr'],
            ['https://swanson.co.uk/shop', 'fr', 'https://swanson.co.uk/fr/shop-fr'],
            ['https://swanson.co.uk/shop', 'fr', 'https://swanson.co.uk/fr/shop-fr'],
        ];
    }
}
