<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\UnexpectedTokenException;
use Falloff\DocBlock\PHPDoc\GenericTag;
use Falloff\DocBlock\Visitor;
use Falloff\DocBlock\Entity;


final class GenericTagTest extends TestCase implements Visitor
{
    function testSingleLine(): void{

        $string = "/** @some-generic-tag John Doe <doe@sub.domain.tld> */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), GenericTag::class );

        $this->assertEquals( $docblock[0]->name, 'some-generic-tag' );
        $this->assertEquals( $docblock[0][0], 'John Doe <doe@sub.domain.tld>' );

    }

    function testMultiLine(): void{

        $string = "/**
            * @some-generic-tag John Doe <doe@sub.domain.tld> 
            * @other-tag
         */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), GenericTag::class );

        $this->assertEquals( $docblock[0]->name, 'some-generic-tag' );
        $this->assertEquals( $docblock[0][0], 'John Doe <doe@sub.domain.tld>' );

        $this->assertEquals( $docblock[1]->name, 'other-tag' );
        $this->assertTrue( empty($docblock[1][0]) );

    }

    function testVisitor(): void{

        $string = "/**
            * @some-generic-tag John Doe <doe@sub.domain.tld> 
         */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $docblock->visit( $this );

    }

    protected $test_plan = [
        'subtestGenericTagVisisted'
        , 'subtestGenericTagValueVisisted'
    ];

    function in( Entity|string|null $el, array $payload, int|string $index ){

        if( empty($this->test_plan) )
            throw new \Exception("Test plan is empty!");

        $test = array_shift( $this->test_plan );

        return $this->$test( $el, $payload, $index );
    }

    function out( Entity|string|null $el, array $payload, int|string $index ){}

    function subtestGenericTagVisisted( $el, $payload, $index ){
        $this->assertEquals($index, 0);
        $this->assertEquals(get_class($el), GenericTag::class);
    }

    function subtestGenericTagValueVisisted( $el, $payload, $index ){
        $this->assertEquals($el, 'John Doe <doe@sub.domain.tld>');
    }

}
