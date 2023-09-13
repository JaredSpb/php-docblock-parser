<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc\Tag\{
    Deprecated,
    Since,
    Version,
};

final class VersionContainingTagsTest extends TestCase
{

    function testVersionContainingOnelinerTags(): void{
        $string = "/** @version 1.0 */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Version::class );
        $this->assertEquals( (string)$docblock[0]->version, '1.0' );



        $string = "/** @version 1.0 (1970-01-31) */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Version::class );
        $this->assertEquals( (string)$docblock[0]->version, '1.0' );
        $this->assertEquals( (string)$docblock[0]->description[0], '(1970-01-31)' );

    }

    function testVersionContainingTagsBase(): void{

        foreach( [

            'deprecated' => Deprecated::class,
            'since' => Since::class,
            'version' => Version::class

        ] as $tag => $classname ){

            $string = "/**
                * @$tag
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( get_class($docblock[0]), $classname );

        }
    }

    function testVersionContainingTagsVersions(): void{

        foreach( [

            'deprecated' => Deprecated::class,
            'since' => Since::class,
            'version' => Version::class

        ] as $tag => $classname ){

            $string = "/**
                * @$tag 1.0
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( (string)$docblock[0]->version, '1.0' );


            $string = "/**
                * @$tag 1.0.277alpha
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( (string)$docblock[0]->version, '1.0.277alpha' );

            $string = "/**
                * @$tag 1.0.277alpha tail
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( (string)$docblock[0]->version, '1.0.277alpha' );

        }
    }

    function testVersionContainingTagsDescription(): void{

        foreach( [

            'deprecated' => Deprecated::class,
            'since' => Since::class,
            'version' => Version::class

        ] as $tag => $classname ){

            $string = "/**
                * @$tag summary
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );

            $this->assertEquals( $docblock[0]->description[0], 'summary' );


            $string = "/**
                * @$tag summary
                *       with some additional string
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->description[0], "summary\n      with some additional string" );

            $string = "/**
                * @$tag summary
                *
                *       with some additional string
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->description[0], "summary\n\n      with some additional string" );


            $string = "/**
                * @$tag summary.
                *       with some additional string
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->description[0], "summary.\n      with some additional string" );

        }
    }

}
