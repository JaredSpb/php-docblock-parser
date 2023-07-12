<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\Visitor;
use Falloff\DocBlock\Entity;
use Falloff\DocBlock\PHPDoc\{
    TextBlock
    , Text
    , Placeholder
    , Func
};

use Falloff\DocBlock\PHPDoc\Tag\{
    Link
    , Method
    , ReturnTag
    , See
};

use Falloff\DocBlock\PHPDoc\Tag\Param as ParamTag;

use Falloff\DocBlock\PHPDoc\Terminal\{
    URL
    , Param
};

final class VisitorTest extends TestCase implements Visitor{

    protected $test_plan = [
        'subTestTextBlock',
        'subTestTextBlockSummary',
        'subTestTextBlockSummaryPayload',
        'subTestTextBlockText',
        'subTestTextBlockTextPreLinkText',
        'subTestTextBlockTextPlaceholder',
        'subTestTextBlockTextPlaceholderLink',
        'subTestTextBlockTextPlaceholderLinkUrl',
        'subTestTextBlockTextPlaceholderLinkDescription',
        'subTestTextBlockTextPlaceholderLinkDescriptionPayload',
        'subTestTextBlockTextPostLinkText',
        'subTestMethod',
        'subTestMethodType',
        'subTestMethodDefinition',
        'subTestMethodName',
        'subTestMethodParam1',
        'subTestMethodParam1Name',
        'subTestMethodParam1Type',
        'subTestMethodParam2',
        'subTestMethodDescription',
        'subTestMethodDescriptionEntry',
        'subTestParamTag1',
        'subTestParamTag1Type',
        'subTestParamTag1Name',
        'subTestParamTag1Description',
        'subTestParamTag1DescriptionText',
        'subTestParamTag2',
        'subTestReturnTag',
        'subTestSeeTag',
    ];

    function testVisitor(): void{

        $string = '/**
        * The summary string
        * having two lines.
        * The description with a {@link proto://domain.tld The Link} element.
        * @method int[]|Some\Class myMethod(int[] $param1, SomeType|null $param2) and method description
        * @param int[] $param1 Param description
        * @param Some\Class $param2 Param2 description
        *                           having two lines
        * @return mixed[] return description
        * @see Something\Here
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $docblock->visit( $this );

    }

    function in( Entity|string $el, array $payload, int|string $index ){
        if( empty( $this->test_plan ) )
            throw new \Exception("Test plan empty!");

        $test = array_shift( $this->test_plan );

        return $this->$test( $el, $payload, $index );

    }
    function out( Entity|string $el, array $payload, int|string $index ){}

    function subTestTextBlock( $el, $payload, $index ){
        $this->assertEquals( get_class($el), TextBlock::class );

        $this->assertEquals( get_class($payload['summary']), Text::class );
        $this->assertEquals( get_class($payload['text']), Text::class );

        return true;
    }

    function subTestTextBlockSummary( $el, $payload, $index ){
        $this->assertEquals( $index, 'summary' );

        $this->assertTrue( empty($payload['multiline']) );

        $this->assertEquals( get_class($el), Text::class );
    }

    function subTestTextBlockSummaryPayload( $el, $payload, $index ){
        $this->assertEquals( $index, 0 );
        $this->assertTrue( empty($payload) );
        $this->assertEquals( $el, "The summary string\nhaving two lines." );
    }

    function subTestTextBlockText( $el, $payload, $index ){
        $this->assertEquals( $index, 'text' );
        $this->assertEquals( get_class($el), Text::class );
        $this->assertTrue( !empty($payload['multiline']) );
    }

    function subTestTextBlockTextPreLinkText( $el, $payload, $index ){
        $this->assertEquals( $index, 0 );
        $this->assertEquals( $el, "The description with a " );
    }

    function subTestTextBlockTextPlaceholder( $el, $payload, $index ){
        $this->assertEquals( $index, 1 );
        $this->assertEquals( get_class($el), Placeholder::class );
        return true;
    }

    function subTestTextBlockTextPlaceholderLink( $el, $payload, $index ){
        $this->assertEquals( $index, 'tag' );
        $this->assertEquals( get_class($el), Link::class );
        return true;
    }

    function subTestTextBlockTextPlaceholderLinkUrl( $el, $payload, $index ){
        $this->assertEquals( $index, 'url' );
        $this->assertEquals( get_class($el), URL::class );
    }

    function subTestTextBlockTextPlaceholderLinkDescription( $el, $payload, $index ){
        $this->assertEquals( $index, 'description' );
        $this->assertEquals( get_class($el), Text::class );
    }

    function subTestTextBlockTextPlaceholderLinkDescriptionPayload( $el, $payload, $index ){
        $this->assertEquals( $index, 0 );
        $this->assertEquals( $el, 'The Link' );
    }

    function subTestTextBlockTextPostLinkText( $el, $payload, $index ){
        $this->assertEquals( $index, 2 );
        $this->assertEquals( $el, ' element.' );
    }

    function subTestMethod( $el, $payload, $index ){
        $this->assertEquals( $index, 1 );
        $this->assertEquals( get_class($el), Method::class );
        return true;
    }

    function subTestMethodType( $el, $payload, $index ){
        $this->assertEquals( $index, 'type' );
        $this->assertEquals( $el, 'int[]|Some\Class' );
    }

    function subTestMethodDefinition( $el, $payload, $index ){
        $this->assertEquals( $index, 'definition' );
        $this->assertEquals( get_class($el), Func::class );
        return true;
    }

    function subTestMethodName( $el, $payload, $index ){
        $this->assertEquals( $index, 'name' );
        $this->assertEquals( $el, 'myMethod' );
        return true;
    }

    function subTestMethodParam1( $el, $payload, $index ){
        $this->assertEquals( get_class($el), Param::class );
        $this->assertEquals( $el->name, '$param1' );
        $this->assertEquals( $el->type, 'int[]' );
        $this->assertEquals( ['name' => '$param1', 'type' => 'int[]'], $payload );
        return true;
    }

    function subTestMethodParam1Name( $el, $payload, $index ){
        $this->assertEquals( $el, '$param1' );
    }
    function subTestMethodParam1Type( $el, $payload, $index ){
        $this->assertEquals( $el, 'int[]' );
    }

    function subTestMethodParam2( $el, $payload, $index ){
        $this->assertEquals( get_class($el), Param::class );
        $this->assertEquals( $el->name, '$param2' );
        $this->assertEquals( $el->type, 'SomeType|null' );
        $this->assertEquals( ['name' => '$param2', 'type' => 'SomeType|null'], $payload );
    }

    function subTestMethodDescription( $el, $payload, $index ){
        $this->assertEquals( get_class($el), Text::class );
        $this->assertEquals( $el[0], 'and method description' );
        return true;
    }
    function subTestMethodDescriptionEntry( $el, $payload, $index ){
        $this->assertEquals( $el, 'and method description' );
    }

    function subTestParamTag1( $el, $payload, $index ){
        $this->assertEquals( get_class($el), ParamTag::class );
        $this->assertEquals( $index, 2 );
        $this->assertEquals( (string)$el->type, 'int[]' );
        $this->assertEquals( (string)$el->name, '$param1' );
        $this->assertEquals( $el->description[0], 'Param description' );

        return true;
    }

    function subTestParamTag1Type( $el, $payload, $index ){
        $this->assertEquals( (string)$el, 'int[]' );
    }
    function subTestParamTag1Name( $el, $payload, $index ){
        $this->assertEquals( (string)$el, '$param1' );
    }

    function subTestParamTag1Description( $el, $payload, $index ){
        $this->assertEquals( get_class($el), Text::class );
    }

    function subTestParamTag1DescriptionText( $el, $payload, $index ){
        $this->assertEquals( $el, 'Param description' );
    }

    function subTestParamTag2( $el, $payload, $index ){
        $this->assertEquals( get_class($el), ParamTag::class );
        $this->assertEquals( $index, 3 );
        $this->assertEquals( (string)$el->type, 'Some\Class' );
        $this->assertEquals( (string)$el->name, '$param2' );
        $this->assertEquals( $el->description[0], "Param2 description\n                          having two lines" );

        return false;
    }


    function subTestReturnTag( $el, $payload, $index ){

        $this->assertEquals( get_class($el), ReturnTag::class );
        $this->assertEquals( (string)$el->type, 'mixed[]' );
        $this->assertEquals( $el->description[0], 'return description' );

    }

    function subTestSeeTag( $el, $payload, $index ){

        $this->assertEquals( get_class($el), See::class );
        $this->assertEquals( (string)$el->ref, 'Something\Here' );
        $this->assertTrue( empty($el->description) );

    }

}

