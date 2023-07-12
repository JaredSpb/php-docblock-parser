<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Tag\{
    Uses
    , UsedBy
};

final class UseRelatedTagsTest extends TestCase{

    function testUseTags(): void{

        foreach([
            'uses' => Uses::class,
            'used-by' => UsedBy::class
        ] as $tag => $klass){

            $string = "/**
                * @$tag Some\Package description.
                *                    with several lines
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );

            $this->assertEquals( get_class($docblock[0]), $klass );
            $this->assertEquals( $docblock[0]->ref, 'Some\Package' );
            $this->assertEquals( $docblock[0]->description[0], "description.\n                   with several lines" );

        }

    }

}

