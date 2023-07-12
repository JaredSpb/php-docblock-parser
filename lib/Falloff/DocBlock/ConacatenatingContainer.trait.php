<?php
namespace Falloff\DocBlock;

trait ConacatenatingContainer{

	use ArrayAlike;

	function append( Entity|string $value ): void{
		if(
			!empty( $this->children )
			and is_string( $this->children[ count( $this->children ) - 1 ] ) 
			and is_string($value)
		){
			$this->children[ count( $this->children ) - 1 ] .= $value;
		} else{
			$this->children[] = $value;
		}
	}

	function trim(){

		if( is_string( $this->children[0] ) )
			$this->children[0] = ltrim($this->children[0]);

		if( is_string( $this->children[ count( $this->children ) - 1 ] ) )
			$this->children[ count( $this->children ) - 1 ] = rtrim($this->children[ count( $this->children ) - 1 ]);


	}
	
}
