<?php
namespace Falloff\DocBlock\PHPDoc;
use Falloff\DocBlock\Visitor;

abstract class Terminal extends \Falloff\DocBlock\Entity {

	protected string $value = '';

	function visit( Visitor $visitor, int|string $index ) : void{
		$visitor->in( $this, $this->getVisitorPayload(), $index );
		$visitor->out( $this, $this->getVisitorPayload(), $index );
	}

	function getVisitorPayload() : array {
		return ['value' => $this->value];
	}

	function getVisitableProperties() : array {
		return [];
	}
	function visitProperties(Visitor $visitor) : void{}
	
	function __toString(){
		return $this->value;
	}
}