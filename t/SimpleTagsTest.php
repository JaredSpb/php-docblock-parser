<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\UnexpectedTokenException;
use Falloff\DocBlock\PHPDoc\Tag\{
    Api,
    Filesource,
    Category,
    Todo,
    Internal,
    Copyright,
    Ignore,
    Subpackage,
};

final class SimpleTagsTest extends TestCase
{
    function testEmptyTags(): void{

        foreach( ['api' => Api::class,'filesource' => Filesource::class] as $tag => $classname ){

            $string = "/**
                * @$tag
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertTrue( get_class($docblock[0]) == $classname );
        }
    }

    function testEmptyTagFollowedByText(): void{

        $string = "/**
            * @api and some text
        */";

        $this->expectException(UnexpectedTokenException::class);
        $this->expectExceptionMessage('Token of type `WORD` with the value of `and` is not supposed to be here');

        $docblock = new Falloff\DocBlock\PHPDoc( $string );


    }


    function testIntroTextFollowedByTag(): void{
        $string = "/**

            * This is summary
            * 
            * This is description
            * @api
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertTrue( count($docblock) == 2);

        $this->assertEquals( $docblock[0]->summary[0], "This is summary" );
        $this->assertEquals( $docblock[0]->text[0], "This is description" );

        $this->assertEquals( get_class($docblock[1]), Api::class );

    }


    function testDescriptedTags():void{

        foreach( [
            'category' => Category::class,
            'copyright' => Copyright::class,
            'todo' => Todo::class,
            'internal' => Internal::class,
            'ignore' => Ignore::class,
            'subpackage' => Subpackage::class
        ] as $tag => $class ){

            $string = "/**
                * @$tag
                *
                * Some description
                * using many lines  (including parenthesis).
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );

            $this->assertEquals( get_class($docblock[0]), $class );

            $this->assertEquals( $docblock[0]->description[0], "Some description\nusing many lines  (including parenthesis).", $class );


            $string = "/**
                * @$tag summary
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->description[0], "summary" );


            $string = "/**
                * @$tag summary
                *
                * Some description
                * using many lines  (including parenthesis).
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->description[0], "summary\n\nSome description\nusing many lines  (including parenthesis)." );

        }

    }



}
