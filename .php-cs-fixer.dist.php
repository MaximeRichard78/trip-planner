<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor', 'node_modules']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony'                    => true,
        'array_syntax'                => ['syntax' => 'short'],
        'ordered_imports'             => true,
        'no_unused_imports'           => true,
        'trailing_comma_in_multiline' => true,
        'line_ending' => true,
    ])
    ->setFinder($finder);