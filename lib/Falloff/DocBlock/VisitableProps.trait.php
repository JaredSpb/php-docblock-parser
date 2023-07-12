<?php
namespace Falloff\DocBlock;

trait VisitableProps{
	
	function visitProperties( Visitor $visitor ) : void {

		$props = $this->getVisitableProperties();

		foreach( $props as $prop ){
			$value = $this->$prop;
			if( is_object( $value ) ){
				$value->visit( $visitor, $prop );
			} else{
				$visitor->in( $value, [], $prop );
				$visitor->out( $value, [], $prop );
			}
		}

	}
}
