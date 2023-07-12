<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\Entity;
use Falloff\DocBlock\PHPDoc\QuotedStr;

final class QuotedStringTest extends TestCase{

    function testQuotedStrings(): void{

        $string = '/**
        * The "summary string\"
        * having two lines. {@this will not be unwrapped} " and a suffix.
        * Plus text body.
        * @return "return description"
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $this->assertEquals( $docblock[0]->summary[0], 'The ' );
        $this->assertEquals( get_class($docblock[0]->summary[1]), QuotedStr::class );
        $this->assertEquals( (string) $docblock[0]->summary[1], "summary string\"\nhaving two lines. {@this will not be unwrapped} " );
        $this->assertEquals( $docblock[0]->text[0], "Plus text body." );
        $this->assertEquals( $docblock[1]->description[0], "return description" );


    }

}

