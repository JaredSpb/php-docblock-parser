<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Hierarchy extends \Falloff\DocBlock\PHPDoc\Terminal {


	static function accept( Token $token ){

		$accept = !in_array( $token->type, ['SPACE', 'NEWLINE', 'DOUBLE_NEWLINE', 'DOT_NEWLINE'] );
		return $accept;

	}

	function process( Token $token ) : ?Token{

		while( $token and !in_array($token->type, ['NEWLINE', 'DOUBLE_NEWLINE', 'DOT_NEWLINE']) ){

			$this->value .= $token->value;
			$token = ($this->stream->get())();
		}

		return $token;

	}

	function __toString(){
		return $this->value;
	}

}