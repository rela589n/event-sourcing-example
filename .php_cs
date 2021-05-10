<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('.docker')
    ->exclude('.githooks')
    ->exclude('docker')
    ->exclude('bootstrap')
    ->exclude('Nova')
    ->exclude('resources')
    ->exclude('storage')
    ->exclude(' bootstrap/cache/services.php')
    ->exclude('.phpstorm.meta.php')
    ->exclude('_ide_helper.php')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
;

return PhpCsFixer\Config::create()
    ->setRules([
        'array_indentation' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => false,
        'blank_line_after_namespace' => true,
        'blank_line_before_return' => true,
        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_anonymous_constructs' => 'same',
            'position_after_control_structures' => 'same',
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'cast_spaces' => ['space' => 'none'],
        'class_attributes_separation' => [
            'elements' => [
                'method'
            ],
        ],
        'class_definition' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => false],
        'declare_equal_normalize' => true,
        'elseif' => true,
        'encoding' => true,
        'fully_qualified_strict_types' => true,
        'function_typehint_space' => true,
        'include' => true,
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'method_argument_space' => true,
        'method_chaining_indentation' => true,
        'multiline_whitespace_before_semicolons' => true,
        'native_function_casing' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_closing_tag' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unneeded_curly_braces' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'object_operator_without_whitespace' => true,
        'php_unit_method_casing' => ['case' => 'camel_case'],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_no_empty_return' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'return_assignment' => true,
        'return_type_declaration' => true,
        'semicolon_after_instruction' => true,
        'single_blank_line_at_eof' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'standardize_increment' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
        'trailing_comma_in_multiline_array' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(false);
