<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\ObligatoryParameterMissingException;
use Falloff\DocBlock\PHPDoc\{
	Placeholder,
};
use Falloff\DocBlock\PHPDoc\Tag\Example;


final class CodeRefTagsTest extends TestCase{


	function testExampleTag(){

		$string = '/**
			* @example location_definition 5 10 description
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

		$this->assertEquals( $docblock[0]->location, 'location_definition' );
		$this->assertEquals( $docblock[0]->start_line, '5' );
		$this->assertEquals( $docblock[0]->number_of_lines, '10' );
		$this->assertEquals( $docblock[0]->description[0], 'description' );


		$string = '/**
			* @example location_definition
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, null );


		$string = '/**
			* @example location_definition description
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, null );
		$this->assertEquals( $docblock[0]->description[0], 'description' );


		$string = '/**
			* @example "path/to file/with\"example.txt" description
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );

		$this->assertEquals( (string)$docblock[0]->location, 'path/to file/with"example.txt' );
		$this->assertEquals( $docblock[0]->description[0], 'description' );


		$string = '/**
			* @example 10
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );

		// In this case the `10` string should be treated as location
		$this->assertEquals( (string)$docblock[0]->location, '10' );

	}

	function testEmptyExampleTag(){

		$string = '/**
			* @example
		*/';


		$this->expectException(ObligatoryParameterMissingException::class);
		$this->expectExceptionMessage('`location` parameter is required for the `example` tag');

		$docblock = new Falloff\DocBlock\PHPDoc( $string );

	}

	function testSourceTag(){

		$string = '/**
			* @source
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertTrue( empty( $docblock[0]->start_line ) );
        $this->assertTrue( empty( $docblock[0]->number_of_lines ) );
        $this->assertTrue( empty( $docblock[0]->description ) );

		$string = '/**
			* @source 5
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, '5' );
        $this->assertTrue( empty( $docblock[0]->number_of_lines ) );
        $this->assertTrue( empty( $docblock[0]->description ) );


		$string = '/**
			* @source 5 10
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, '5' );
        $this->assertEquals( $docblock[0]->number_of_lines, '10' );
        $this->assertTrue( empty( $docblock[0]->description ) );


		$string = '/**
			* @source 5 10 description
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, '5' );
        $this->assertEquals( $docblock[0]->number_of_lines, '10' );
        $this->assertEquals( $docblock[0]->description[0], 'description' );

		$string = '/**
			* @source 10 description
			*            having several
			*            lines
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, '10' );
        $this->assertTrue( empty( $docblock[0]->number_of_lines ) );
        $this->assertEquals( $docblock[0]->description[0], "description\n           having several\n           lines" );

		$string = '/**
			* @source description.
			*         having several
			*         lines
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertTrue( empty( $docblock[0]->start_line ) );
        $this->assertTrue( empty( $docblock[0]->number_of_lines ) );
        $this->assertEquals( $docblock[0]->description[0], "description.\n        having several\n        lines" );

		$string = '/**
			* @source 5 10 description
			*
			*              having several
			*              lines
		*/';

		$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( $docblock[0]->start_line, '5' );
        $this->assertEquals( $docblock[0]->number_of_lines, '10' );
        $this->assertEquals( $docblock[0]->description[0], "description\n\n             having several\n             lines" );

	}


}
