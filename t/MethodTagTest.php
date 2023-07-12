<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\PHPDoc\Func;
use Falloff\DocBlock\PHPDoc\Tag\{
    Method
};

final class MethodTagTest extends TestCase{

    function testMethodTag(): void{

        // All the props
        $string = '/**
            * @method static int[]|Some\Class myMethod( int[] $param1, Some\Class|null $param2 ) description.
            *                                                                                    split to lines
            *
            *                                                                                    more than needed
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, true );
        $this->assertEquals( $docblock[0]->type, 'int[]|Some\Class' );

        $this->assertEquals( get_class($docblock[0]->definition), Func::class );
        $this->assertEquals( $docblock[0]->definition->name, 'myMethod' );

        $this->assertEquals( $docblock[0]->definition[0]->type, 'int[]' );
        $this->assertEquals( $docblock[0]->definition[0]->name, '$param1' );

        $this->assertEquals( $docblock[0]->definition[1]->type, 'Some\Class|null' );
        $this->assertEquals( $docblock[0]->definition[1]->name, '$param2' );

        $this->assertEquals( 
            $docblock[0]->description[0], "description.\n"
            ."                                                                                   split to lines\n\n"
            ."                                                                                   more than needed" );

        // Non-static
        $string = '/**
            * @method int[]|Some\Class myMethod( int[] $param1, Some\Class|null $param2 ) description.
            *                                                                                    split to lines
            *
            *                                                                                    more than needed
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, false );
        $this->assertEquals( $docblock[0]->type, 'int[]|Some\Class' );

        $this->assertEquals( get_class($docblock[0]->definition), Func::class );
        $this->assertEquals( $docblock[0]->definition->name, 'myMethod' );

        $this->assertEquals( $docblock[0]->definition[0]->type, 'int[]' );
        $this->assertEquals( $docblock[0]->definition[0]->name, '$param1' );

        $this->assertEquals( $docblock[0]->definition[1]->type, 'Some\Class|null' );
        $this->assertEquals( $docblock[0]->definition[1]->name, '$param2' );

        $this->assertEquals( 
            $docblock[0]->description[0], "description.\n"
            ."                                                                                   split to lines\n\n"
            ."                                                                                   more than needed" );

        // No return type
        $string = '/**
            * @method myMethod( int[] $param1, Some\Class|null $param2 ) description.
            *                                                                                    split to lines
            *
            *                                                                                    more than needed
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, false );
        $this->assertTrue( empty($docblock[0]->type) );

        $this->assertEquals( get_class($docblock[0]->definition), Func::class );
        $this->assertEquals( $docblock[0]->definition->name, 'myMethod' );

        $this->assertEquals( $docblock[0]->definition[0]->type, 'int[]' );
        $this->assertEquals( $docblock[0]->definition[0]->name, '$param1' );

        $this->assertEquals( $docblock[0]->definition[1]->type, 'Some\Class|null' );
        $this->assertEquals( $docblock[0]->definition[1]->name, '$param2' );

        $this->assertEquals( 
            $docblock[0]->description[0], "description.\n"
            ."                                                                                   split to lines\n\n"
            ."                                                                                   more than needed" );

        // No description
        $string = '/**
            * @method myMethod( int[] $param1, Some\Class|null $param2 )
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, false );
        $this->assertTrue( empty($docblock[0]->type) );

        $this->assertEquals( get_class($docblock[0]->definition), Func::class );
        $this->assertEquals( $docblock[0]->definition->name, 'myMethod' );

        $this->assertEquals( $docblock[0]->definition[0]->type, 'int[]' );
        $this->assertEquals( $docblock[0]->definition[0]->name, '$param1' );

        $this->assertEquals( $docblock[0]->definition[1]->type, 'Some\Class|null' );
        $this->assertEquals( $docblock[0]->definition[1]->name, '$param2' );

        $this->assertTrue( empty($docblock[0]->description) );

        // Type only
        $string = '/**
            * @method sometype
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, false );
        $this->assertTrue( empty($docblock[0]->definition) );
        $this->assertTrue( empty($docblock[0]->description) );

        $this->assertEquals( (string)$docblock[0]->type, 'sometype' );

        // Type and description
        $string = '/**
            * @method sometype description
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );
        $this->assertEquals( get_class($docblock[0]), Method::class );
        $this->assertEquals( $docblock[0]->static, false );
        $this->assertTrue( empty($docblock[0]->definition) );

        $this->assertEquals( (string)$docblock[0]->type, 'sometype' );
        $this->assertEquals( $docblock[0]->description[0], 'description' );

    }
}

