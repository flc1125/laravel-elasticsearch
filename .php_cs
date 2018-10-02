<?php

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'array_syntax' => array('syntax' => 'short'),
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'yoda_style' => false,
        'phpdoc_summary' => false,
        'not_operator_with_successor_space' => true,
        'no_extra_consecutive_blank_lines' => true,
        'general_phpdoc_annotation_remove' => true,
        // 'ordered_class_elements' => true,
        'binary_operator_spaces' => array(
            'align_double_arrow' => true,
            'align_equals' => true,
            'default' => 'align_single_space_minimal',
        ),
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('_*')
            ->exclude('vendor')
            ->exclude('storage')
            ->exclude('resources')
            ->exclude('public')
            ->in(__DIR__)
    )
;