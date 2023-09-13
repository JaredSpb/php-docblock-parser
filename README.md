# php-docblock-parser

This a standalone library for parsing docblocks. Only PHPDoc v3 are supported at the moment.

## Installation

```
composer require jared/php-docblock-parser
```

## Usage

```php
$raw = '/**
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

$docblock = new Falloff\DocBlock\PHPDoc( $raw );

foreach( $docblock as $index => $element )
	print_r($element);

```

## Traversing

### Direct access

The parsed docblock is a recursive structure consisting of text blocks and tag structures. The latter may contain own text blocks
that might contain inline tags, that might contain...
This structure might be traversed without additional tools in the following way:

```php

$queue = [$docblock];

while( !empty( $queue ) ){

    $element = array_shift($queue);

	if( is_object($element) and get_class( $element ) == Falloff\DocBlock\PHPDoc\TextBlock::class ){
		// Gives 'The cummary string....'
		print $element->summary[0] . "\n"; 

		// Gives 'The Description...'
		print $element->text[0] . "\n"; 

		// Refers to Placeholder object, containing the Link tag
		print get_class($element->text[1]) . "\n";

		// The 'proto://domain.tld' string 
		print $element->text[1]->tag->url . "\n"; 

		// The 'The Link' string
		print $element->text[1]->tag->description[0] . "\n"; 
	}

	if( is_object($element) and get_class( $element ) == Falloff\DocBlock\PHPDoc\Tag\Method::class ){
		 // The 'int[]|Some\Class' string
		print $element->type . "\n"; ;

		 // 'myMethod'
		print $element->definition->name . "\n"; ;

		// The params are accessed like the `definition` was an array:

		// 'int[]'
		print $element->definition[0]->type . "\n";
		 // '$param1'
		print $element->definition[0]->name . "\n";
	}

	// Adding param's tags descriptions to the queue
	if( is_object($element) and get_class( $element ) == Falloff\DocBlock\PHPDoc\Tag\Param::class ){

		// Elements in the description might be objects or regular strings
		foreach( $element->description as $param_description_chunk ){
			$queue[] = $param_description_chunk;
		}
	}


	if( is_scalar($element) ){
		// Will produce following two strings:
		// 'The following is a regular scalar string: `Param description`'
		// 'The following is a regular scalar string: `Param2 description`''
		print 'The following is a regular scalar string: `' . $element . "`\n";
	}

	if( is_object($element) ){
	    foreach ($element as $child) {
	        $queue[] = $child;
	    }		
	}

}

```

This approach might be used in some cases, but is looks pretty inconvinient. 


### Via Visitor

This package ships the `Visitor` interface that might be used to make traversal 
easier:


```php

$visitor = new class implements Falloff\DocBlock\Visitor{

	function in( Falloff\DocBlock\Entity|string|null $entity, array $payload, int|string $idx ) {

		print 'Index is: ' . $idx . "\n";

		print (
			is_object( $entity )
			? "Entity is an object of class " . get_class( $entity ) . "\n"
			: (
				is_null( $entity )
				? "Entity is NULL\n"
				: "Entity is a string containing: " . $entity . "\n"
			)			
		);

		print "Payload is: \n";
		print_r($payload);

		print "\n";

		return true;

	}

	function out( Falloff\DocBlock\Entity|string|null $entity, array $payload, int|string $idx){}
};

$docblock->visit( $visitor );

```

The visitor must implement the `in` and `out` methods. In the traversal process these methods are called with the current
entity itself, which might be the instance representing the docblock or its component. The `$payload` param is an array containing some 
internal structure of the current element, like a `name` and `type` of the `@param` tag. The `$idx` param is an index of a current element inside its parent structure. This might be integer for plain containers like `Text`, or a name of a property like `url` for `@link` tag.
The return value of the `in` method matters in the following way:

- pure `false` value tells to stop traversing current element, its children or properties and move to the next element in the tree
- pure `true` value tells to traverse through current element properties i.e. the `ref` and `description` for the `@see` tag
- any other value will make traversal skip element's properties and move to the element's children if any, or just move forward otherwise

Traversing without returning a value should be enough while `$payload` contains all the data required to process the element. If thats not enough, make sure to return `true` to access the element's properties in the next iteration.


