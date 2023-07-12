<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc\{
	TextBlock,
	Placeholder
};
use Falloff\DocBlock\PHPDoc\Tag\Example;


final class PlaceholdersTest extends TestCase{


	function testTextPlaceholderTag(){

		$string = '/**
    		* This summary has inline {@example location_definition 5 10 description} and some following data
		*/';

    	$docblock = new Falloff\DocBlock\PHPDoc( $string );
    	$this->assertEquals( get_class($docblock[0]), TextBlock::class );

    	$this->assertEquals( $docblock[0]->summary[0], 'This summary has inline ');
    	$this->assertEquals( get_class($docblock[0]->summary[1]), Placeholder::class );
    	$this->assertEquals( get_class($docblock[0]->summary[1]->tag), Example::class );


		$placeholder_tag = $docblock[0]->summary[1]->tag;
    	$this->assertEquals( $placeholder_tag->location, 'location_definition' );
    	$this->assertEquals( $placeholder_tag->start_line, '5' );
    	$this->assertEquals( $placeholder_tag->number_of_lines, '10' );
    	$this->assertEquals( $placeholder_tag->description[0], 'description' );


    	$this->assertEquals( $docblock[0]->summary[2], ' and some following data' );

	}

	function testTagDescriptionPlaceholder(){

		$string = '/**
    		* @example "path/to file/with\"example.txt" description
    		* This string has inline {@example location_definition 5 10 description} and some following data
		*/';

    	$docblock = new Falloff\DocBlock\PHPDoc( $string );
		$this->assertEquals( (string)$docblock[0]->location, 'path/to file/with"example.txt' );

		$this->assertEquals( $docblock[0]->description[0], "description\nThis string has inline " );

		$this->assertEquals( get_class($docblock[0]->description[1]), Placeholder::class );
		$this->assertEquals( get_class($docblock[0]->description[1]->tag), Example::class );

		$placeholder_tag = $docblock[0]->description[1]->tag;

    	$this->assertEquals( $placeholder_tag->location, 'location_definition' );
    	$this->assertEquals( $placeholder_tag->start_line, '5' );
    	$this->assertEquals( $placeholder_tag->number_of_lines, '10' );
    	$this->assertEquals( $placeholder_tag->description[0], 'description' );

    	$this->assertEquals( $docblock[0]->description[2], ' and some following data' );

	}

}




