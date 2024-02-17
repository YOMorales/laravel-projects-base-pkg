<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenPrivateMethods;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Interfaces;
use NunoMaduro\PhpInsights\Domain\Metrics\Code\Classes as ClassesCode;
use NunoMaduro\PhpInsights\Domain\Metrics\Code\Code;
use NunoMaduro\PhpInsights\Domain\Metrics\Code\Comments;
use NunoMaduro\PhpInsights\Domain\Metrics\Code\Functions;
use NunoMaduro\PhpInsights\Domain\Metrics\Code\Globally;
use NunoMaduro\PhpInsights\Domain\Metrics\Style\Style;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Arrays\DisallowLongArraySyntaxSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Classes\DuplicateClassNameSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\EmptyStatementSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\ForLoopShouldBeWhileLoopSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\JumbledIncrementerSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UnconditionalIfStatementSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UnnecessaryFinalModifierSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\ByteOrderMarkSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\InlineHTMLSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineEndingsSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\OneClassPerFileSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\OneInterfacePerFileSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterCastSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Metrics\NestingLevelSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\CharacterBeforePHPOpeningTagSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Strings\UnnecessaryStringConcatSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\VersionControl\GitMergeConflictSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\WhiteSpace\DisallowTabIndentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Arrays\ArrayBracketSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\ClassFileNameSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Classes\SelfMemberReferenceSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\EmptyCatchCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\PostStatementCommentSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\CSS\SemicolonSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Operators\IncrementDecrementUsageSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Operators\ValidLogicalOperatorsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\DiscouragedFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\EvalSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\GlobalKeywordSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\InnerFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\LowercasePHPFunctionsSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\PHP\NonExecutableCodeSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Scope\StaticThisUsageSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionOpeningBraceSpaceSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\FunctionSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\LogicalOperatorSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\MemberVarSpacingSniff;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\WhiteSpace\SuperfluousWhitespaceSniff;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use SlevomatCodingStandard\Sniffs\Arrays\TrailingArrayCommaSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\UselessConstantTypeHintSniff;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
    */

    'preset' => 'laravel',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
    | "atom", "vscode".
    |
    | If you have another IDE that is not in this list but which provide an
    | url-handler, you could fill this config with a pattern like this:
    |
    | myide://open?url=file://%f&line=%l
    |
    */

    'ide' => 'vscode',

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        './app/Base/Middleware',
        './app/Base/Providers',
        './app/Http/Middleware',
        './app/Providers',
        './tests/Data',
    ],

    'add' => [
        // Architecture Metrics
        Classes::class => [
            // tests that the file name and the name of the class contained within the file match
            ClassFileNameSniff::class,
            // class names should not be duplicated
            DuplicateClassNameSniff::class,
            // detected empty statement. For example, an if statement that does nothing
            EmptyStatementSniff::class,
            //
            ForbiddenFinalClasses::class,
            // detects for-loops that can be simplified to a while-loop
            ForLoopShouldBeWhileLoopSniff::class,
            // There must be one class per file
            OneClassPerFileSniff::class,
            // detects unnecessary 'final' modifiers inside of final classes
            UnnecessaryFinalModifierSniff::class,
        ],
        Interfaces::class => [
            // there must be one interface per file
            OneInterfacePerFileSniff::class,
        ],
        // Code Metrics
        ClassesCode::class => [
            // checks that $this is not used inside a static function
            StaticThisUsageSniff::class,
        ],
        Code::class => [
            // detects byte-order marks (BOMs) that may corrupt application work
            ByteOrderMarkSniff::class,
            // checks that there are no characters before the '<?php' opening tag
            CharacterBeforePHPOpeningTagSniff::class,
            // the use of eval() is discouraged
            EvalSniff::class,
            // detects artifacts left by bad git merges, such as: <<<< HEAD
            GitMergeConflictSniff::class,
            // ensures that the ++ operators are used when possible
            IncrementDecrementUsageSniff::class,
            // ensures the whole file is PHP only, with no inline HTML (blade files will be ignored)
            InlineHTMLSniff::class,
            // detects the usage of one and the same incrementer into an outer and an inner loop
            JumbledIncrementerSniff::class,
            // Warns about 'detached' code that will never be executed
            NonExecutableCodeSniff::class,
            // detects statement conditions that are only set to one of the constant values true or false
            UnconditionalIfStatementSniff::class,
            // avoids things like: 'text' . 'text'
            UnnecessaryStringConcatSniff::class,
            // ensures logical operators `and` and `or` are not used
            ValidLogicalOperatorsSniff::class,
        ],
        Comments::class => [
            // cannot put @param mixed
            DisallowMixedTypeHintSniff::class,
            // makes sure that empty catch clauses have at least a comment
            EmptyCatchCommentSniff::class,
            // comments cannot be at the end of a line of code
            PostStatementCommentSniff::class,
        ],
        Functions::class => [
            // warns about the use of debug functions (shouldn't be there in production code)
            DiscouragedFunctionsSniff::class,
            // ensures that functions within functions are never used
            InnerFunctionsSniff::class,
            // ensures that native php function names are lowercase
            LowercasePHPFunctionsSniff::class,
        ],
        Globally::class => [
            // forbids use of the global keyword
            GlobalKeywordSniff::class,
        ],
        // Style Metrics
        Style::class => [
            // cannot have spaces around array brackets, like this $array[ 0 ]
            ArrayBracketSpacingSniff::class,
            // avoids array long syntax like: array()
            DisallowLongArraySyntaxSniff::class,
            // self explanatory:
            DisallowTabIndentSniff::class,
            // checks that there is no empty line after the opening brace of a function
            FunctionOpeningBraceSpaceSniff::class,
            // checks that end-of-line characters are '\n'
            LineEndingsSniff::class,
            // verifies that logical operators (&& || etc) have valid spacing surrounding them
            LogicalOperatorSpacingSniff::class,
            // ensures that there is an empty line after each property declaration in a class
            MemberVarSpacingSniff::class,
            // checks the 'self::' keyword for proper spacing, casing, etc.
            SelfMemberReferenceSniff::class,
            // ensures there is no whitespace before a semicolon
            SemicolonSpacingSniff::class,
            // ensures that there is a space after a typecast keyword
            SpaceAfterCastSniff::class,
            // ensures that there is a space after a 'not' operator
            SpaceAfterNotSniff::class,
            // makes sure that arrays end with a trailing comma
            TrailingArrayCommaSniff::class,
        ],
    ],

    'remove' => [
        // removed: short ternary operators are useful
        DisallowShortTernaryOperatorSniff::class,
        DocCommentSpacingSniff::class,
        // removed: forbade public properties and required instead setters/getters, which sometimes is cumbersome
        ForbiddenPublicPropertySniff::class,
        // removed: sometimes extra blank lines help make the code more readable
        NoExtraBlankLinesFixer::class,
        // removed: sometimes double-quotes are desirable for simple strings (e.g. to highlight SQL in VSCode)
        SingleQuoteFixer::class,
        // removed: impeded interfaces from having 'Interface' suffix and other things
        SuperfluousInterfaceNamingSniff::class,
        // removed: forbade declaring var type in docblocks of constants
        UselessConstantTypeHintSniff::class,
        // all of these below were added by default:
        // removed: enforced that class import statements be sorted alphabetically
        AlphabeticallySortedUsesSniff::class,
        // removed: not always we want to declare strict types
        DeclareStrictTypesSniff::class,
        // removed: this avoided us from creating 'helper' functions
        ForbiddenDefineFunctions::class,
        // removed: used to forbid non-abstract and non-final classes
        ForbiddenNormalClasses::class,
        // removed: this disallowed traits
        ForbiddenTraits::class,
        // TODO: removed for now, but later see if these can be caught with Larastan
        ParameterTypeHintSniff::class,
        PropertyTypeHintSniff::class,
        ReturnTypeHintSniff::class,
    ],

    'config' => [
        // warns about complex code
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 7
        ],
        ForbiddenPrivateMethods::class => [
            'title' => 'The usage of private methods is not idiomatic in Laravel.',
        ],
        FunctionLengthSniff::class => [
            'maxLinesLength' => 30
        ],
        // ensures that there is an empty line after each method in a class
        FunctionSpacingSniff::class => [
            'spacing' => 1
        ],
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120
        ],
        MemberVarSpacingSniff::class => [
            'spacingBeforeFirst' => 0
        ],
        // warns when nesting levels are higher than 4
        NestingLevelSniff::class => [
            'nestingLevel' => 4
        ],
        SuperfluousWhitespaceSniff::class => [
            'ignoreBlankLines' => true
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Here you may define a level you want to reach per `Insights` category.
    | When a score is lower than the minimum level defined, then an error
    | code will be returned. This is optional and individually defined.
    |
    */

    'requirements' => [
       'min-quality' => 80,
       'min-complexity' => 70,
       'min-architecture' => 80,
       'min-style' => 80,
       'disable-security-check' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Threads
    |--------------------------------------------------------------------------
    |
    | Here you may adjust how many threads (core) PHPInsights can use to perform
    | the analyse. This is optional, don't provide it and the tool will guess
    | the max core number available. It accepts null value or integer > 0.
    |
    */

    'threads' => null,

];
