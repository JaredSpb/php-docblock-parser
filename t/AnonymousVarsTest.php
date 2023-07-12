<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Tag\{
    Throws
    , ReturnTag
};

final class AnonymousVarsTest extends TestCase{

    function testVariableDescribingTags(): void{

        foreach( [
            'return' => ReturnTag::class,
            'throws' => Throws::class
        ] as $tag => $klass ){

            $string = "/** @$tag */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( get_class($docblock[0]), $klass );

            $string = "/** @$tag int */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'int' );

            $string = "/** 
                * @$tag int|Some\Claz\Name|string[] 
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'int|Some\Claz\Name|string[]' );


            $string = "/** 
                * @$tag The word 'The' in this description gonna
                *         be the return type <not supposed to fail>
            */";
            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'The' );
            $this->assertEquals( $docblock[0]->description[0], "word ");
            $this->assertEquals( $docblock[0]->description[1], "The");
            $this->assertEquals( $docblock[0]->description[2], "in this description gonna\n        be the return type <not supposed to fail>");


        }

    }


}
