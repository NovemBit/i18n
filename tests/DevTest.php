<?php

namespace NovemBit\tests\i18n;

use Doctrine\DBAL\ConnectionException;
use NovemBit\i18n\component\translation\exceptions\UnsupportedLanguagesException;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\helpers\Environment;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class BasicTest
 */
class DevTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param $url
     * @param $language
     * @param $final
     * @throws ConnectionException
     * @throws UnsupportedLanguagesException
     * @throws InvalidArgumentException
     */
    public function testAdd(&$global, $key, $final): void
    {
        $this->assertEquals(
            $final,
            Environment::global($global, $key)
        );
    }

    public function setUp(): void
    {
        foreach ($this->additionProvider() as &$item){
            Environment::global($item[0],$item[1],$item[2]);
        }
    }

    public function additionProvider(): array
    {
        return [
            [&$_GET,'vaspur','123'],
            [&$_POST,'vaspur','123'],
            [&$_SERVER,'vaspur','123'],
        ];
    }
}
