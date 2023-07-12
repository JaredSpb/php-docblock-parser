<?php
use PHPUnit\Framework\TestCase;
use Falloff\DocBlock\Entity;
use Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\PHPDoc\{
    TextBlock
    , Text
    , Placeholder
    , Func
};

use Falloff\DocBlock\PHPDoc\Tag\{
    Link
    , Method
    , ReturnTag
    , See
};

use Falloff\DocBlock\PHPDoc\Tag\Param as ParamTag;

use Falloff\DocBlock\PHPDoc\Terminal\{
    URL
    , Param
};

final class TraversalTest extends TestCase{

    function testTraversal(): void{

        $class_test_sequence = [
            PHPDoc::class,
            TextBlock::class,
            Method::class,
            ParamTag::class,
            ParamTag::class,
            ReturnTag::class,
            See::class,
        ];

        $string = '/**
        * The summary string
        * having two lines.
        * The description with a {@link proto://domain.tld The Link} element.
        * @method int[]|Some\Class myMethod(int[] $param1, SomeType|null $param2) and method description
        * @param int[] $param1 Param description
        * @param Some\Class $param2 Param2 description
        *                           having two lines
        * @return mixed[] return description
        * @see Something\Here
        */';

        $docblock = new Falloff\DocBlock\PHPDoc( $string );

        $queue = [$docblock];

        while( !empty( $queue ) ){

            $element = array_shift($queue);

            $this->assertEquals( get_class( $element ), array_shift( $class_test_sequence ) );

            foreach ($element as $child) {
                $queue[] = $child;
            }

        }

    }

}

