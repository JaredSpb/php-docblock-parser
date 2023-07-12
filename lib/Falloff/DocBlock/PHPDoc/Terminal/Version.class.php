<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Version extends \Falloff\DocBlock\PHPDoc\Terminal {

	static function accept( Token $token ){

		$accept = in_array(
			$token->type, 
			['VERSION_ALIKE']
		);

		return $accept;
	}

	function process( Token $token ) : ?Token {

		$this->value = $token->value;
		return ($this->stream->get())();

	}

	function __toString(){
		return $this->value;
	}

}