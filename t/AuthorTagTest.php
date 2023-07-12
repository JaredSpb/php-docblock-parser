<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\UnexpectedTokenException;
use Falloff\DocBlock\PHPDoc\Tag\{
    Author,
};

final class AuthorTagTest extends TestCase
{
    function testSingleLine(): void{

        $string = "/** @author John Doe <doe@sub.domain.tld>*/";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Author::class );
        $this->assertEquals( $docblock[0]->author, 'John Doe' );
        $this->assertEquals( $docblock[0]->email, 'doe@sub.domain.tld' );

    }

    function testMultiLine(): void{

        $string = "/**
            * @author John Doe <doe@sub.domain.tld>
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Author::class );
        $this->assertEquals( $docblock[0]->author, 'John Doe' );
        $this->assertEquals( $docblock[0]->email, 'doe@sub.domain.tld' );

    }

    function testEmpty(): void{

        $string = "/**
            * @author
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Author::class );
        $this->assertTrue( empty( $docblock[0]->author ));
        $this->assertTrue( empty( $docblock[0]->email ));

    }

    function testEmail(): void{

        $string = "/**
            * @author <doe@sub.domain.tld>
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Author::class );
        $this->assertTrue( empty( $docblock[0]->author ));
        $this->assertEquals( $docblock[0]->email, 'doe@sub.domain.tld' );

    }

    function testName(): void{

        $string = "/**
            * @author John Doe
        */";

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Author::class );
        $this->assertTrue( empty( $docblock[0]->email ));
        $this->assertEquals( $docblock[0]->author, 'John Doe' );

    }
}
