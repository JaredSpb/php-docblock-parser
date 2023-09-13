<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\Tag\{
    Package
    , Version
    , License
    , Author
};

final class FullBlockTest extends TestCase{

    function testVariableDescribingTags(): void{

    	$string = '/**
			* SomeLib is a library doing something usefull

			* @package Some\Package
			* @license LicenceName
			* @version 1.0.0 (2022-09-13)
			* @author Author <author@domain.tld>
			*/';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );


        $this->assertEquals( $docblock[0]->summary[0], 'SomeLib is a library doing something usefull' );

        $this->assertEquals( get_class($docblock[1]), Package::class );
        $this->assertEquals( $docblock[1]->hierarchy, 'Some\\Package' );


        $this->assertEquals( get_class($docblock[2]), License::class );
		$this->assertEquals( $docblock[2]->license, 'LicenceName' );

        $this->assertEquals( get_class($docblock[3]), Version::class );
		$this->assertEquals( $docblock[3]->version, '1.0.0' );
		$this->assertEquals( $docblock[3]->description[0], '(2022-09-13)' );

        $this->assertEquals( get_class($docblock[4]), Author::class );
		$this->assertEquals( $docblock[4]->author, 'Author' );
		$this->assertEquals( $docblock[4]->email, 'author@domain.tld' );

    }

}


