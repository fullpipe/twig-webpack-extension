<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('tests')
    ->in([__DIR__ . '/src']);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'psr4' => true,
        'array_syntax' => ['syntax' => 'short'],
        'mb_str_functions' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'strict_comparison' => false,
        'strict_param' => false,
        'native_function_invocation' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_types_order' => true,
        'phpdoc_order' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],
        'explicit_string_variable' => true,
        'simple_to_complex_string_variable' => true,
        // extra
        'modernize_types_casting' => true,
        'method_chaining_indentation' => true,
        'linebreak_after_opening_tag' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(false)
;
