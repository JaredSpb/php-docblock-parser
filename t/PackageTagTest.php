<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc\Tag\{
	Package,	
};
use Falloff\DocBlock\UnexpectedTokenException;

final class PackageTagTest extends TestCase{


	function testPackage(){

		$string = '/**
			* @package Some package
			* @package Some package \ Some subpackage
			* @package SomePackage\SomeSubpackage
			* @package Some package . Some subpackage
			* @package SomePackage.SomeSubpackage
			* @package SomePackage_SomeSubpackage
		*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $checks = [
			'Some package',
			'Some package \ Some subpackage',
			'SomePackage\SomeSubpackage',
			'Some package . Some subpackage',
			'SomePackage.SomeSubpackage',
			'SomePackage_SomeSubpackage',
        ];


        foreach ($docblock as $i => $tag) {
        	$this->assertEquals( (string)$tag->hierarchy, $checks[$i] );
        }
        

	}

	function testInvalidTextPlacement(){

		$string = '/**
			* @package Some package
			*                this will trigger exception
		*/';

		$this->expectException(UnexpectedTokenException::class);
		$this->expectExceptionMessage('Token of type `WORD` with the value of `this` is not supposed to be here');


        $docblock = new Falloff\DocBlock\PHPDoc( $string );
       

	}

}