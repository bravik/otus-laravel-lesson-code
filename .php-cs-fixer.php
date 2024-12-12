<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                           => true,
        'array_syntax'                     => ['syntax' => 'short'],
        'combine_consecutive_unsets'       => true,
        'class_attributes_separation'      => ['elements' => ['method' => 'one']],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'author', 'package', 'expectedException', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp',
            ],
        ],
        'global_namespace_import' => [
            'import_classes'   => true,
            'import_functions' => null,
            'import_constants' => null,
        ],
        'no_empty_phpdoc'             => true,
        'no_superfluous_phpdoc_tags'  => ['allow_mixed' => false, 'allow_unused_params' => false],
        'phpdoc_line_span'            => ['property' => 'single'],
        'heredoc_to_nowdoc'           => true,
        'list_syntax'                 => ['syntax' => 'short'],
        'blank_line_before_statement' => ['statements' => ['if', 'continue', 'declare', 'return', 'throw', 'try', 'yield']],
        'no_useless_else'             => true,
        'no_useless_return'           => true,
        'operator_linebreak'          => ['position' => 'beginning'],
        'ordered_class_elements'      => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property',
                'construct',
                'destruct',
                'method_abstract',
                'method',
            ],
        ],
        'ordered_imports'                     => ['imports_order' => ['const', 'class', 'function']],
        'php_unit_test_class_requires_covers' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order'                        => true,
        'semicolon_after_instruction'         => true,
        'yoda_style'                          => false,
        'binary_operator_spaces'              => [
            'default'   => 'single_space',
            'operators' => [
                '='   => 'align_single_space_minimal',
                '+='  => 'align_single_space_minimal',
                '=>'  => 'align_single_space_minimal',
                '===' => null,
            ],
        ],
        'concat_space' => [
            'spacing' => 'one',
        ],
        'types_spaces' => ['space' => 'none'],
    ])
    ->setRiskyAllowed(false)
    ->setFinder($finder)
;
