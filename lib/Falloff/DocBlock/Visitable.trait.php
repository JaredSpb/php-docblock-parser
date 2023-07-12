<?php
namespace Falloff\DocBlock;

trait Visitable{
	use VisitableProps;
	
	function visit( Visitor $visitor, int|string $index ) : void {

		$rv = $visitor->in( $this, $this->getVisitorPayload(), $index );

		if( $rv === true ){
			$this->visitProperties( $visitor );
		}

		$visitor->out( $this, $this->getVisitorPayload(), $index );

	}
}
