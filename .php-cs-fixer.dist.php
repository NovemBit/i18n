<?php

declare(strict_types=1);

use PhpCsFixer\Config;

include __DIR__ . '/vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
                           ->in(['src']);

return (new Config())
    ->setRules(
        [
            '@Symfony'              => true,
            '@PSR12'                => true,
            'no_alternative_syntax' => true,
            'strict_comparison'     => true,
            'strict_param'          => true,
            'declare_strict_types'  => true,
            'no_useless_else'       => true,
            'simplified_if_return'  => true,
            'yoda_style'            => false,
            'array_syntax'          => true,
            'trim_array_spaces'     => true
        ]
    )
    ->setFinder($finder)
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
;
