<?php
namespace Falloff\DocBlock;
use Falloff\Tokenizer\{Stream, Token};

abstract class Entity{
	protected \WeakReference $stream;

	function __construct( Stream $stream ){
		$this->stream = \WeakReference::create($stream);
	}

	abstract static function accept( Token $token );
	
	abstract function process( Token $token ) : ?Token;

	abstract function visit( Visitor $visitor, int|string $index ) : void;

	abstract function visitProperties( Visitor $visitor ) : void;
	abstract function getVisitableProperties() : array;

	abstract function getVisitorPayload() : array;

}
