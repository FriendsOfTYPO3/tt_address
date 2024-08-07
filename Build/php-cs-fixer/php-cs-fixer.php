<?php

$finder = PhpCsFixer\Finder::create()
    ->in(realpath(__DIR__ . '/../../'));

$config = new PhpCsFixer\Config();
$config
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRiskyAllowed(true)
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PER-CS' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => true,
        'single_space_around_construct' => true,
        'control_structure_braces' => true,
        'control_structure_continuation_position' => true,
        'declare_parentheses' => true,
        'no_multiple_statements_per_line' => true,
        'statement_indentation' => ['stick_comment_to_next_continuous_control_statement' => true],
        'no_extra_blank_lines' => true,
        'cast_spaces' => true,
        'compact_nullable_type_declaration' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'none'],
        'dir_constant' => true,
        'lowercase_cast' => true,
        'modernize_types_casting' => true,
        'native_function_casing' => true,
        'new_with_parentheses' => true,
        'no_alias_functions' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_null_property_initialization' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_superfluous_elseif' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_nullsafe_operator' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => true,
        'php_unit_construct' => ['assertions' => ['assertEquals', 'assertSame', 'assertNotEquals', 'assertNotSame']],
        'php_unit_mock_short_will_return' => true,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'phpdoc_no_access' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'return_type_declaration' => ['space_before' => 'none'],
        'single_quote' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_trait_insert_per_statement' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'whitespace_after_comma_in_array' => ['ensure_single_space' => true],
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => true,
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'braces_position' => true,
        'constant_case' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_type_declaration_casing' => true,
        'no_unset_cast' => true,
        'short_scalar_cast' => true,
        'class_attributes_separation' => ['elements' => ['method' => 'one']],
        'class_definition' => true,
        'single_class_element_per_statement' => true,
        'visibility_required' => true,
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => true,
        'single_line_comment_spacing' => true,
        'elseif' => true,
        'empty_loop_body' => ['style' => 'braces'],
        'no_alternative_syntax' => ['fix_non_monolithic_code' => true],
        'no_unneeded_braces' => ['namespaces' => true],
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'function_declaration' => true,
        'lambda_not_used_import' => true,
        'method_argument_space' => ['on_multiline' => 'ignore'],
        'no_spaces_after_function_name' => true,
        'nullable_type_declaration' => ['syntax' => 'question_mark'],
        'nullable_type_declaration_for_default_null_value' => true,
        'no_unneeded_import_alias' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'list_syntax' => true,
        'blank_line_after_namespace' => true,
        'blank_lines_before_namespace' => true,
        'clean_namespace' => true,
        'binary_operator_spaces' => true,
        'no_space_around_double_colon' => true,
        'object_operator_without_whitespace' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'full_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        'no_closing_tag' => true,
        'align_multiline_comment' => true,
        'no_superfluous_phpdoc_tags' => ['allow_hidden_params' => true, 'remove_inheritdoc' => true],
        'phpdoc_indent' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_param_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,
        'no_useless_return' => true,
        'return_assignment' => true,
        'multiline_whitespace_before_semicolons' => true,
        'simple_to_complex_string_variable' => true,
        'array_indentation' => true,
        'blank_line_between_import_groups' => true,
        'method_chaining_indentation' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_whitespace' => true,
        'single_blank_line_at_eof' => true,
        'spaces_inside_parentheses' => true,
        'type_declaration_spaces' => true,
        'types_spaces' => true,
        \PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoTrailingCommaInSinglelineFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessDirnameCallFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\PhpUnitDedicatedAssertFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\PhpUnitAssertArgumentsOrderFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer::name() => true,
        \PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer::name() => true,
    ])
    ->setFinder($finder);

return $config;
