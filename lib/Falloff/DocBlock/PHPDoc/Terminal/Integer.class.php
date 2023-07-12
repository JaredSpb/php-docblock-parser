<?php
namespace Falloff\DocBlock\PHPDoc\Terminal;
use Falloff\Tokenizer\Token;
use Falloff\Tokenizer\Stream;

class Integer extends \Falloff\DocBlock\PHPDoc\Terminal {


	static function accept( Token $token ){

		$accept = in_array(
			$token->type, 
			['INT']
		);

		return $accept;
	}

	function process( Token $token ): ?Token{

		$this->value = $token->value;
		return ($this->stream->get())();

	}

}