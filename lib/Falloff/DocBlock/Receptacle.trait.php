<?php
namespace Falloff\DocBlock;

trait Receptacle{

	use VisitableProps;
	use ArrayAlike;


	protected function addChild( Entity $child ){
		$this->children[] = $child;
	}

	function last(){
		return $this->children[ count( $this->children ) - 1 ];
	}

	function visit( Visitor $visitor, int|string $index ) : void {
		$rv = $visitor->in( $this, $this->getVisitorPayload(), $index );
		
		if( $rv === false ){
			$visitor->out( $this, $this->getVisitorPayload(), $index );
			return;
		}
		if( $rv === true ){
			$this->visitProperties( $visitor );
		}

		foreach( $this->children as $i => $child ){
			if( is_scalar($child) ){
				$visitor->in( $child, [], $i );
				$visitor->out( $child, [], $i );
			} else{
				$child->visit( $visitor, $i );
			}
		}
		$visitor->out( $this, $this->getVisitorPayload(), $index );
	}




}