<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\{
	ObligatoryParameterMissingException
};
use Falloff\DocBlock\PHPDoc\{
	Placeholder,
};
use Falloff\DocBlock\PHPDoc\Tag\{
	License
	, Link
	, See
};
use Falloff\DocBlock\PHPDoc\Terminal;


final class URLContainingTagsTest extends TestCase{

	function testLicenseTag(){

		$string = '/**
			* @license
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $this->assertEquals( get_class($docblock[0]), License::class );


        $string = '/**
			* @license MIT
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( $docblock[0]->license, 'MIT' );

        $string = '/**
			* @license https://mit-license.org/
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( (string)$docblock[0]->url, 'https://mit-license.org/' );


        $string = '/**
			* @license https://mit-license.org/ MIT
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( (string)$docblock[0]->license, 'MIT' );
        $this->assertEquals( (string)$docblock[0]->url, 'https://mit-license.org/' );

	}

	function testEmptyLinkTag(){
		$string = '/**
			* @link
		*/';

        $this->expectException(ObligatoryParameterMissingException::class);
        $this->expectExceptionMessage('`url` parameter is required for the `link` tag');
        $docblock = new Falloff\DocBlock\PHPDoc( $string );

	}

	function testLinkTag(){

        $string = '/**
			* @link proto://domain.tld/some/path?q=d
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Link::class );
        $this->assertEquals( (string)$docblock[0]->url, 'proto://domain.tld/some/path?q=d' );

        $string = '/**
			* @link proto://domain.tld/some/path?q=d Link description
			*                                        spaning several lines
			*                                        containing inline {@link p://q.tld/ link   }
			*                                        with extras
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Link::class );
        $this->assertEquals( (string)$docblock[0]->url, 'proto://domain.tld/some/path?q=d' );

        $this->assertEquals( (string)$docblock[0]->description[0], "Link description\n                                       spaning several lines\n                                       containing inline " );



        $this->assertEquals( get_class($docblock[0]->description[1]), Placeholder::class );
        $this->assertEquals( get_class($docblock[0]->description[1]->tag), Link::class );

        $link = $docblock[0]->description[1]->tag;
        $this->assertEquals( $link->url, 'p://q.tld/' );
        $this->assertEquals( $link->description[0], 'link' );

	}

	function testEmptySeeTag(){
		$string = '/**
			* @see
		*/';

        $this->expectException(ObligatoryParameterMissingException::class);
        $this->expectExceptionMessage('`ref` parameter is required for the `see` tag');
        $docblock = new Falloff\DocBlock\PHPDoc( $string );
	}

	function testSeeTag(){

		$string = '/**
			* @see meaningless here
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), See::class );
        $this->assertEquals( get_class($docblock[0]->ref), Terminal\GenericWord::class );
        $this->assertEquals( $docblock[0]->ref, 'meaningless' );
        $this->assertEquals( $docblock[0]->description[0], 'here' );


		$string = '/**
			* @see true://url.tld/here
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]->ref), Terminal\URL::class );
        $this->assertEquals( (string)$docblock[0]->ref, 'true://url.tld/here' );

        $this->assertTrue( empty( $docblock[0]->description ) );


		$string = '/**
			* @see true://url.tld/here and some description too
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]->ref), Terminal\URL::class );
        $this->assertEquals( (string)$docblock[0]->ref, 'true://url.tld/here' );

        $this->assertEquals( (string)$docblock[0]->description[0], 'and some description too' );


		$string = '/**
			* @see SomeClass::someMethod/$param
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]->ref), Terminal\GenericWord::class );
        $this->assertEquals( (string)$docblock[0]->ref, 'SomeClass::someMethod/$param' );

	}


}
