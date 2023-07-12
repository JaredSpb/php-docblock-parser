<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Tag\{
    VarTag
    , Property
    , PropertyRead
    , PropertyWrite
    , Param
};

final class NamedVarsTagsTest extends TestCase{

    function testVariableDescribingTags(): void{

        foreach( [
            'var' => VarTag::class
            ,'property' => Property::class
            ,'property-read' => PropertyRead::class
            ,'property-write' => PropertyWrite::class
            ,'param' => Param::class
        ] as $tag => $klass ){


            $string = "/** @$tag */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( get_class($docblock[0]), $klass );


            $string = "/** 
                * @$tag int|Some\Class|string[] 
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'int|Some\Class|string[]' );

            $string = "/** 
                * @$tag int|Some\Class|string[] \$varname
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'int|Some\Class|string[]' );
            $this->assertEquals( $docblock[0]->name, '$varname' );

            $string = "/** 
                * @$tag \$varname
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->name, '$varname' );


            $string = "/** 
                * @$tag The wonderfull PHPDoc makes the word 'The' to be the type
                *       and 'wonderfull' to be the name (kinda constant name)
            */";

            $docblock = new Falloff\DocBlock\PHPDoc( $string );
            $this->assertEquals( $docblock[0]->type, 'The' );
            $this->assertEquals( $docblock[0]->name, 'wonderfull' );
            $this->assertEquals( $docblock[0]->description[0], "PHPDoc makes the word " );
            $this->assertEquals( (string) $docblock[0]->description[1], "The" );
            $this->assertEquals( $docblock[0]->description[2], "to be the type\n      and " );
            $this->assertEquals( $docblock[0]->description[3], "wonderfull" );
            $this->assertEquals( $docblock[0]->description[4], "to be the name (kinda constant name)" );             


        }

    }


}
