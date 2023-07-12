<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc;

final class NoTagsTest extends TestCase
{

    function testOneLine(): void{

        $string = "/** The heading line */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $this->assertEquals( get_class($docblock), PHPDoc::class );
        $this->assertEquals( get_class($docblock[0]), PHPDoc\TextBlock::class );
        
        $this->assertEquals( $docblock[0]->summary[0], "The heading line" );

    }

    function testSummary(): void{

        $string = "/**
            * The heading line
        **/";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $this->assertEquals( get_class($docblock), PHPDoc::class );
        $this->assertEquals( get_class($docblock[0]), PHPDoc\TextBlock::class );

        $this->assertEquals( $docblock[0]->summary[0], "The heading line" );

        foreach( $docblock as $i => $el ){
            $this->assertEquals( get_class($el), PHPDoc\TextBlock::class );
        }

    }

    function testDescription():void{

        $string = "/**
            * The heading line
            *
            * Some description
            * using many lines  (including parenthesis).
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( $docblock[0]->summary[0], "The heading line" );
        $this->assertEquals( $docblock[0]->text[0], "Some description\nusing many lines  (including parenthesis)." );


        $string = "/**
            * The heading. line.
            * Some description
            * using many lines  (including parenthesis).
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( $docblock[0]->summary[0], "The heading. line." );
        $this->assertEquals( $docblock[0]->text[0], "Some description\nusing many lines  (including parenthesis)." );

    }

}
